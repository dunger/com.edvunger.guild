<?php
namespace calendar\system\event\listener;
use calendar\data\category\CalendarCategory;
use guild\data\member\MemberList;
use guild\data\role\RoleList;
use guild\system\guild\GuildHandler;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild.calendar
 */
class GuildCalendarUpcomingEventPageListener implements IParameterizedEventListener {
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters) {
        if ($eventName == 'readParameters') {
            $eventObj->templateName = 'guildUpcomingEventList';
        } elseif ($eventName == 'assignVariables') {
            $roleList = $memberList = $mainMemberID = $mainRoleID = null;

            if ($eventObj->categoryID) {
                $guildID = (isset(CalendarCategory::getCategory($eventObj->categoryID)->getData()['additionalData']['guildID'])) ? CalendarCategory::getCategory($eventObj->categoryID)->getData()['additionalData']['guildID'] : 0;

                if ($guildID) {
                    $game = GuildHandler::getInstance()->getGuild($guildID)->getGame();
                    $roleList  = new RoleList();
                    $roleList->getActive($game->gameID);

                    $memberList = new MemberList();
                    $memberList->getActiveByUserID(WCF::getUser()->userID, $guildID);

                    if (!empty($memberList->objectIDs)) {
                        foreach ($memberList->getObjects() as $member) {
                            if ($member->isMain == true) {
                                $mainMemberID = $member->memberID;
                                $mainRoleID = $member->groupID;
                            }

                        }
                    }
                }
            }

            WCF::getTPL()->assign([
                'mainRoleID' => $mainRoleID,
                'mainMemberID' => $mainMemberID,
                'memberList' => ($memberList != null && $memberList->objectIDs) ? $memberList : null,
                'roleList' => $roleList
            ]);
        }
    }
}