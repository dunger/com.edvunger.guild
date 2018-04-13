<?php
namespace guild\acp\page;
use guild\data\wow\instance\InstanceList;
use wcf\page\SortablePage;
use wcf\system\event\EventHandler;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowInstanceListPage extends SortablePage {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.game.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $objectListClassName = InstanceList::class;

    /**
     * @inheritDoc
     */
    public $defaultSortField = 'instanceID';

    /**
     * @inheritDoc
     */
    public $defaultSortOrder = 'ASC';

    /**
     * @inheritDoc
     */
    public $itemsPerPage = 50;

    /**
     * @inheritDoc
     */
    public $validSortFields = ['name', 'title', 'mapID', 'difficulty', 'isRaid'];
}