<?php
namespace guild\system\cronjob;
use guild\data\member\MemberAction;
use guild\data\member\MemberList;
use guild\system\game\GameHandler;
use wcf\data\cronjob\Cronjob;
use wcf\data\user\User;
use wcf\data\user\UserAction;
use wcf\system\cronjob\AbstractCronjob;

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

        $games = GameHandler::getInstance()->getGames();

        /*
         * Delete old Member
         */
        $oldMemberList = new MemberList();
        $oldMemberList->getInactive();

        if (!empty($oldMemberList->objectIDs)) {
            foreach ($oldMemberList->getObjects() as $oldMember) {
                if (!empty($games->objectIDs)) {
                    foreach ($games->getObjects() as $game) {
                        $class = new $game->apiClass;
                        $class->removeMember($oldMember->memberID);
                    }
                }

                // remove user from group
                if ($oldMember->userID) {
                    $user = new User($oldMember->userID);
                    $groupsIDs = array_diff($user->getGroupIDs(), [$oldMember->groupID]);
                    $userAction = new UserAction([$user], 'update', [
                        'groups' => $groupsIDs
                    ]);
                    $userAction->executeAction();
                }

                unset($class, $statisticList, $statisticAction, $userAction);
            }

            // delete old member
            $memberAction = new MemberAction($oldMemberList->objectIDs, 'delete');
            $memberAction->executeAction();
        }
        unset($oldMemberList);

        if (!empty($games)) {
            foreach ($games as $game) {
                if ($game->isActive == false || empty($game->apiClass)) {
                    continue;
                }

                $class = new $game->apiClass;
                $class->cronjob($game);
            }
        }

        return;
    }
}