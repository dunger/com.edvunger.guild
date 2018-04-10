<?php
namespace calendar\system\event\listener;
use guild\data\member\MemberList;
use guild\system\guild\GuildHandler;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild.calendar
 */
class GuildCalendarEventPageListener implements IParameterizedEventListener {
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters) {
        if (!isset($eventObj->event->getCategory()->getData()['additionalData']['guildID']) || $eventObj->event->getCategory()->getData()['additionalData']['guildID'] == 0) {
            // nothing to do
            return;
        }

        $guildID = $eventObj->event->getCategory()->getData()['additionalData']['guildID'];
        $guild = GuildHandler::getInstance()->getGuild($guildID);

        $userIDs = $memberIDs = [];
        $decisionYes = $decisionMaybe = $decisionNo = 0;

        if (isset($eventObj->participantList)) {
            $participantList = $eventObj->participantList;
        } else {
            $participantList = $eventObj;
        }

        if (isset($participantList->objects)) {
            foreach ($participantList->objects as $member) {
                $userIDs[] =  $member->getUserProfile()->userID;
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

        $eventObj->templateName = 'guildEvent';
        WCF::getTPL()->assign([
            'roleList'				 => $guild->getRoles(),
            'decisionYes'			 => $decisionYes,
            'decisionMaybe'			 => $decisionMaybe,
            'decisionNo'			 => $decisionNo,
            'participantData'        => $memberList->getMemberByIDs($memberIDs, $guildID),
            'missingParticipantList' => $missingMemberList->getMemberByIDs($memberIDs, $guildID, $userIDs, true),
        ]);
    }
}