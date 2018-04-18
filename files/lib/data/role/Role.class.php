<?php
namespace guild\data\role;
use guild\system\game\GameHandler;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $roleID	    unique id of the role
 * @property-read	integer     	$gameID     id of the game the role belongs to
 * @property-read	string		    $name		name of the role
 * @property-read	integer		    $isActive	is 1 if the role is active
 */
class Role extends DatabaseObject {
    /**
     * @inheritDoc
     */
    public $game = null;

    /**
     * Returns the game
     *
     * @return	Game
     */
    public function getGame() {
        if ($this->game === null && $this->gameID) {
            $this->game = GameHandler::getInstance()->getGame($this->gameID);
        }

        return $this->game;
    }

    /**
     * @inheritDoc
     */
    public function getTitle() {
        return WCF::getLanguage()->get($this->name);
    }
}