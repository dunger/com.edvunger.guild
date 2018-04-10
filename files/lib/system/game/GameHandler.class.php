<?php
namespace guild\system\game;
use guild\data\game\Game;
use guild\system\cache\builder\GameCacheBuilder;
use wcf\system\SingletonFactory;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GameHandler extends SingletonFactory {
    /**
     * cached games
     * @var	Game|null
     */
    protected $games = null;

    /**
     * @return	Game|null
     */
    public function getGames() {
        return $this->games;
    }

    /**
     * Returns the game with the given id or `null` if no such game exists.
     *
     * @param	integer		$gameID
     * @return	Game|null
     */
    public function getGame($gameID) {
        if (isset($this->games[$gameID])) {
            return $this->games[$gameID];
        }

        return null;
    }

    /**
     * Returns the game with the given tag or `null` if no such game exists.
     *
     * @param	string		$tag
     * @return	Game|null
     */
    public function getGameByTag($tag) {
        foreach ($this->games as $game) {
            if ($game->tag == $tag) {
                return $game;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function init() {
        $this->games = GameCacheBuilder::getInstance()->getData([]);
    }

    /**
     * Reloads the category cache.
     */
    public function reloadCache() {
        GameCacheBuilder::getInstance()->reset();

        $this->init();
    }
}
