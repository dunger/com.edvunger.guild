<?php
namespace guild\data\game;
use guild\system\game\api\DefaultApi;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $gameID	    unique id of the game
 * @property-read	string		    $name		name of the game
 * @property-read	string		    $apiClass	api class of the game
 * @property-read	string		    $apiKey		api key of the game
 * @property-read	integer		    $isActive   is 1 if the game is active
 */
class Game extends DatabaseObject {
    /**
     * @inheritDoc
     */
    public static function getGameByName($name) {
        $sql = "SELECT	*
			FROM	guild".WCF_N."_game
			WHERE	name = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$name]);
        $row = $statement->fetchArray();
        if (!$row) $row = [];

        return new self(null, $row);
    }

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