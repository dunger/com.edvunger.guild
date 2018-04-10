<?php
namespace guild\system\role;
use guild\data\role\Role;
use guild\system\cache\builder\RoleCacheBuilder;
use wcf\system\SingletonFactory;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class RoleHandler extends SingletonFactory {
    /**
     * cached games
     * @var	Role|null
     */
    protected $roles = null;

    /**
     * @return	Role|null
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Returns the role with the given id or `null` if no such role exists.
     *
     * @param	integer		$roleID
     * @return	Role|null
     */
    public function getRole($roleID) {
        if (isset($this->roles[$roleID])) {
            return $this->roles[$roleID];
        }

        return null;
    }

    /**
     * Returns the role with the given gameID or `null` if no such role exists.
     *
     * @param	integer		$gameID
     * @return	Role|null
     */
    public function getRolesByGame($gameID) {
        $roles = null;
        foreach ($this->roles as $role) {
            if ($role->gameID == $gameID) {
                $roles[$role->roleID] = $role;
            }
        }

        return $roles;
    }

    /**
     * @inheritDoc
     */
    protected function init() {
        $this->roles = RoleCacheBuilder::getInstance()->getData([]);
    }

    /**
     * Reloads the category cache.
     */
    public function reloadCache() {
        RoleCacheBuilder::getInstance()->reset();

        $this->init();
    }
}
