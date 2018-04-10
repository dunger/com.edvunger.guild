<?php
namespace calendar\data\event\date;
use guild\data\member\Member;
use guild\data\member\MemberList;
use guild\data\role\RoleList;
use calendar\data\event\date\participation\EventDateParticipation;
use calendar\data\event\date\participation\EventDateParticipationAction;
use calendar\data\event\date\participation\ViewableEventDateParticipationList;
use guild\system\guild\GuildHandler;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild.calendar
 */
class GuildEventDateAction extends EventDateAction {
    /**
     * @inheritDoc
     */
    public function validateSave() {
        parent::validateSave();

        if (!isset($this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID']) || $this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID'] == 0) {
            $this->parameters['guildRole'] = null;
            return;
        }
        $guildID = $this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID'];

        $this->readInteger('guildMember', true);
        $this->readInteger('guildRole', true);

        $activeMember = new MemberList();
        $activeMember->getActiveByUserID(WCF::getUser()->userID, $guildID);

        if (empty($activeMember->objectIDs)) {
            $mainCharacter = Member::getMain(WCF::getUser()->userID, $guildID);
            if ($mainCharacter !== false) {
                $this->parameters['guildMember'] = $mainCharacter->memberID;
                $this->parameters['guildRole'] = $mainCharacter->roleID;
            } else {
                throw new UserInputException('member');
            }
        } else {
            if ($activeMember->search($this->parameters['guildMember']) === null) {
                throw new UserInputException('member');
            }

            $roleList  = new RoleList();
            $roleList->getActive(GuildHandler::getInstance()->getGuild($guildID)->getGame()->gameID);
            if ($roleList->search($this->parameters['guildRole']) === null) {
                throw new UserInputException('role');
            }
        }

        $roleList  = new RoleList();
        $roleList->getActive(GuildHandler::getInstance()->getGuild($guildID)->getGame()->gameID);
        if ($roleList->search($this->parameters['guildRole']) === null) {
            throw new UserInputException('role');
        }
    }

    /**
     * Fetches the participation form.
     *
     * @return	mixed[]
     */
    public function getParticipationForm() {
        if (!isset($this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID']) || $this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID'] == 0) {
            return parent::getParticipationForm();
        }
        $guildID = $this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID'];
        $mainMemberID = $mainRoleID = 0;

        $roleList  = new RoleList();
        $roleList->getActive(GuildHandler::getInstance()->getGuild($guildID)->getGame()->gameID);

        $memberList = new MemberList();
        $memberList->getActiveByUserID(WCF::getUser()->userID, $guildID);
        if (!empty($memberList->objectIDs)) {
            foreach ($memberList->getObjects() as $member) {
                if ($member->isMain == true) {
                    $mainMemberID = $member->memberID;
                    $mainRoleID = $member->roleID;
                }

            }
        }

        WCF::getTPL()->assign([
            'roleList' => $roleList,
            'mainMemberID' => ($this->eventDateParticipation !== null && $this->eventDateParticipation->mainMemberID !== null) ? $this->eventDateParticipation->mainMemberID : $mainMemberID,
            'mainRoleID' => ($this->eventDateParticipation !== null && $this->eventDateParticipation->guildRoleID !== null) ? $this->eventDateParticipation->guildRoleID : $mainRoleID,
            'memberList' => ($memberList != null && $memberList->objectIDs) ? $memberList : null,
        ]);

        return parent::getParticipationForm();
    }

    /**
     * @inheritDoc
     */
    public function save() {
        if (!isset($this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID']) || $this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID'] == 0) {
            return parent::save();
        }
        $guildID = $this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID'];
        parent::save();

        $participation = EventDateParticipation::getParticipation($this->eventDateEditor->eventDateID, ($this->user === null ? WCF::getUser()->userID : $this->user->userID));
        $objectAction = new EventDateParticipationAction([$participation], 'update', ['data' => [
            'guildMemberID' => $this->parameters['guildMember'],
            'guildRoleID' => $this->parameters['guildRole']
        ]]);
        $objectAction->executeAction();

        $participantList = new ViewableEventDateParticipationList();
        $participantList->getConditionBuilder()->add('eventDateID = ?', [$this->eventDateEditor->eventDateID]);
        $participantList->readObjects();

        $guild = GuildHandler::getInstance()->getGuild($guildID);

        $userIDs = [];
        $decisionYes = $decisionMaybe = $decisionNo = 0;

        if (isset($participantList->objects)) {
            foreach ($participantList->objects as $member) {
                $userIDs[] = $member->getUserProfile()->userID;
                $memberIDs[] = $member->guildMemberID;

                if ($member->decision == 'yes') {
                    $decisionYes++;
                }
                else if ($member->decision == 'maybe') {
                    $decisionMaybe++;
                }
                else if ($member->decision == 'no') {
                    $decisionNo++;
                }
            }
        }

        $memberList = new MemberList();
        $missingMemberList = new MemberList();

        WCF::getTPL()->assign([
            'event' => $this->eventDateEditor->getEvent(),
            'eventDate' => new EventDate($this->eventDateEditor->eventDateID),
            'participantList' => $participantList,
            'roleList' => $guild->getRoles(),
            'decisionYes' => $decisionYes,
            'decisionMaybe' => $decisionMaybe,
            'decisionNo' => $decisionNo,
            'participantData' => $memberList->getMemberByIDs($memberIDs, $guildID),
            'missingParticipantList' => $missingMemberList->getMemberByIDs($userIDs, $guildID, $userIDs, true),
        ]);

        return [
            'template' => WCF::getTPL()->fetch('guildEventDateParticipationList', 'calendar')
        ];
    }
}