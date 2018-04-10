<?php
namespace guild\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */

class RequestListener implements IParameterizedEventListener {
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters) {
        if ($className == 'wcf\system\WCF' && $eventName == 'initialized' &&
            isset($_POST['className']) && $_POST['className'] == 'calendar\data\event\date\EventDateAction' &&
            isset($_POST['actionName']) && $_POST['actionName'] == 'save')
        {
            $_POST['className'] = 'calendar\data\event\date\GuildEventDateAction';
        } else if ($className == 'wcf\system\WCF' && $eventName == 'initialized' &&
                   isset($_POST['className']) && $_POST['className'] == 'calendar\data\event\date\EventDateAction' &&
                   isset($_POST['actionName']) && $_POST['actionName'] == 'getParticipationForm')
        {
            $_POST['className'] = 'calendar\data\event\date\GuildEventDateAction';
        }
    }

}