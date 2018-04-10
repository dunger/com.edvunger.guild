<?php
namespace guild\system\cache\builder;
use guild\data\role\RoleList;
use wcf\system\cache\builder\AbstractCacheBuilder;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class RoleCacheBuilder extends AbstractCacheBuilder {
    /**
     * @inheritDoc
     */
    public function rebuild(array $parameters) {
        $list = new RoleList();
        $list->getConditionBuilder()->add('isActive = 1', []);
        $list->readObjects();
        return $list->getObjects();
    }
}
