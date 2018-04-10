<?php
namespace guild\system\avatar;
use guild\system\cache\builder\AvatarCacheBuilder;
use wcf\system\SingletonFactory;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class AvatarHandler extends SingletonFactory {
    /**
     * cached avatars
     * @var	Avatar[]
     */
    protected $avatars = [];

    /**
     * Returns the category with the given id or `null` if no such category exists.
     *
     * @param	integer		$avatarID
     * @return	Avatar|null
     */
    public function getAvatar($avatarID) {
        if (isset($this->avatars[$avatarID])) {
            return $this->avatars[$avatarID];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function init() {
        $this->avatars = AvatarCacheBuilder::getInstance()->getData([]);
    }

    /**
     * Reloads the category cache.
     */
    public function reloadCache() {
        AvatarCacheBuilder::getInstance()->reset();

        $this->init();
    }
}
