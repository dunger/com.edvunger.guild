<?php
namespace guild\system\guild;
use guild\data\guild\Guild;
use guild\system\cache\builder\GuildCacheBuilder;
use wcf\system\SingletonFactory;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GuildHandler extends SingletonFactory {
    /**
     * cached games
     * @var	Guild|null
     */
    protected $guilds = null;

    /**
     * @return	Guild|null
     */
    public function getGuilds() {
        $guilds = [];
        foreach ($this->guilds as $guild){
            if ($guild->getGame()->isActive == true) {
                $guilds[$guild->guildID] = $guild;
            }
        }

        return (sizeof($guilds) ? $guilds : null);
    }

    /**
     * Returns the role with the given id or `null` if no such guild exists.
     *
     * @param	integer		$roleID
     * @return	Guild|null
     */
    public function getGuild($guildID) {
        if (isset($this->guilds[$guildID])) {
            return $this->guilds[$guildID];
        }

        return null;
    }

    /**
     * Returns the guild with the given gameID or `null` if no such guild exists.
     *
     * @param	integer		$gameID
     * @return	[]|null
     */
    public function getGuildByGameID($gameID) {
        $guilds = [];
        foreach ($this->guilds as $guild){
            if ($guild->getGame()->gameID == $gameID) {
                $guilds[$guild->guildID] = $guild;
            }
        }

        return (sizeof($guilds) ? $guilds : null);
    }

    /**
     * @inheritDoc
     */
    protected function init() {
        $this->guilds = GuildCacheBuilder::getInstance()->getData([]);
    }

    /**
     * Reloads the category cache.
     */
    public function reloadCache() {
        GuildCacheBuilder::getInstance()->reset();

        $this->init();
    }
}
