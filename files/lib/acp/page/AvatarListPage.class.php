<?php
namespace guild\acp\page;
use guild\data\avatar\AvatarList;
use wcf\page\SortablePage;
use wcf\system\event\EventHandler;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class AvatarListPage extends SortablePage {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.avatar.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $objectListClassName = AvatarList::class;

    /**
     * @inheritDoc
     */
    public $defaultSortField = 'avatarID';

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
    public $validSortFields = ['avatarID', 'gameID', 'name', 'image'];
}