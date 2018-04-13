<?php
namespace guild\system\cronjob;
use guild\data\member\MemberList;
use guild\system\game\GameHandler;
use wcf\data\cronjob\Cronjob;
use wcf\data\user\User;
use wcf\data\user\UserAction;
use wcf\system\cronjob\AbstractCronjob;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class UpdateGuildCronjob extends AbstractCronjob {

    /**
     * @inheritDoc
     */
    public function execute(Cronjob $cronjob) {
        parent::execute($cronjob);

        /*
         * Delete old Member
         */
        $oldMemberList = new MemberList();
        $oldMemberList->getInactive();

        if (!empty($oldMemberList->objectIDs)) {
            $oldMemberIDs = [];
            foreach ($oldMemberList->getObjects() as $oldMember) {
                $oldMemberIDs[$oldMember->guildID][] = $oldMember->memberID;

                // remove user from group
                if ($oldMember->userID) {
                    $user = new User($oldMember->userID);
                    $groupsIDs = array_diff($user->getGroupIDs(), [$oldMember->groupID]);
                    $userAction = new UserAction([$user], 'update', [
                        'groups' => $groupsIDs
                    ]);
                    $userAction->executeAction();
                }
            }

            // delete old member
            if (!empty($oldMemberIDs)) {
                foreach ($oldMemberIDs as $guildID => $memberIDs) {
                    $sql = "DELETE FROM guild".WCF_N."_member
                            WHERE	memberID IN (?)
                            AND     guildID = ?";
                    $statement = WCF::getDB()->prepareStatement($sql);
                    $statement->execute([implode(',', $memberIDs), $guildID]);
                }
            }
        }

        /*
         * run game cronjobs
         */
        $games = GameHandler::getInstance()->getGames();
        if (!empty($games)) {
            foreach ($games as $game) {
                if ($game->isActive == false || empty($game->apiClass)) {
                    continue;
                }

                $class = $game->getApiClass();
                $class->cronjob($game);
            }
        }

        return;
    }
}