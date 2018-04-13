<?php
namespace guild\data\game;
use guild\system\game\api\DefaultApi;
use wcf\data\DatabaseObject;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class Game extends DatabaseObject {

    /**
     * @inheritDoc
     */
    public function getApiClass() {
        if (!empty($this->apiClass)) {
            return new $this->apiClass;
        } else {
            return new DefaultApi();
        }
    }

    /**
     * @inheritDoc
     */
    public function getApiClassButtons() {
        return $this->getApiClass()->getButtons();
    }

    /**
     * @inheritDoc
     */
    public function getApiClassGuildButtons() {
        return $this->getApiClass()->getGuildButtons();
    }
}