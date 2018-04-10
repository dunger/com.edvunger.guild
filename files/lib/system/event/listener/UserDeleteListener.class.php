<?php
namespace guild\system\event\listener;
use guild\data\member\Member;
use guild\data\member\MemberAction;
use wcf\system\event\listener\IParameterizedEventListener;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */

class UserDeleteListener implements IParameterizedEventListener {
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters) {
        if ($className == 'wcf\data\user\UserAction' && $eventObj->getActionName() == 'delete') {
            foreach ($eventObj->getObjects() as $object) {
                $member = Member::getMemberByUserID($object->userID);

                if ($member->memberID) {
                    $action = new MemberAction([$member], 'update', [
                        'data' => [
                            'userID' => null,
                            'groupID' => null,
                            'roleID' => null
                        ]
                    ]);
                    $action->executeAction();
                }
            }
        }
    }
}