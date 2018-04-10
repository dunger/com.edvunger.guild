<?php
namespace calendar\data\event\date;
use calendar\data\event\date\participation\EventDateParticipation;
use calendar\data\event\date\participation\EventDateParticipationAction;
use guild\data\member\Member;
use guild\data\member\MemberList;
use guild\data\role\RoleList;
use guild\system\guild\GuildHandler;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild.calendar
 */
class GuildEventDateQuickAction extends EventDateAction {
	/**
	 * Validates parameters to save the participation settings.
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
        $mainCharacter = Member::getMain(WCF::getUser()->userID, $guildID);

        if ($this->parameters['guildMember'] == 0 || empty($activeMember->objectIDs)) {
            if ($mainCharacter === false) {
                throw new UserInputException('member');
            }

            $this->parameters['guildMember'] = $mainCharacter->memberID;
        } else {
            if ($activeMember->search($this->parameters['guildMember']) === null) {
                throw new UserInputException('member');
            }
        }

        if ($this->parameters['guildRole'] == 0 || empty($activeMember->objectIDs)) {
            if ($mainCharacter === false) {
                throw new UserInputException('role');
            }

            $this->parameters['guildRole'] = $mainCharacter->roleID;
        } else {
            $roleList = new RoleList();
            $roleList->getActive(GuildHandler::getInstance()->getGuild($guildID)->getGame()->gameID);
            if ($roleList->search($this->parameters['guildRole']) === null) {
                throw new UserInputException('role');
            }
        }
    }

	/**
	 * Saves the participation settings and returns the rendered template of the participation list.
	 *
	 * @return	string[]
	 */
	public function save() {
        parent::save();

        if (isset($this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID']) && $this->eventDateEditor->getEvent()->getCategory()->getData()['additionalData']['guildID'] != 0) {
            $participation = EventDateParticipation::getParticipation($this->eventDateEditor->eventDateID, ($this->user === null ? WCF::getUser()->userID : $this->user->userID));
            $objectAction = new EventDateParticipationAction([$participation], 'update', ['data' => [
                'guildMemberID' => $this->parameters['guildMember'],
                'guildRoleID' => $this->parameters['guildRole']
            ]]);
            $objectAction->executeAction();
        }

		return ['eventID' => $this->eventDateEditor->eventDateID, 'decision' => $this->parameters['decision']];
	}
}
