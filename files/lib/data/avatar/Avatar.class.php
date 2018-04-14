<?php
namespace guild\data\avatar;
use guild\system\avatar\AvatarHandler;
use guild\system\game\GameHandler;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $avatarID	    unique id of the avatar
 * @property-read	integer     	$gameID	        game id to which the avatar belongs
 * @property-read	string		    $name		    name of the guild
 * @property-read	string		    $image	        avatar image
 * @property-read	string	        $autoAssignment	automatic assignment from the api
 * @property-read	integer		    $isActive	    is 1 if the avatar is active
 */
class Avatar extends DatabaseObject {
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