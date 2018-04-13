<?php
namespace guild\acp\page;
use guild\data\guild\GuildList;
use wcf\system\event\EventHandler;
use wcf\page\SortablePage;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GuildListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'guild.acp.menu.guild.list';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.guild.canManageGuild'];
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = GuildList::class;
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortField = 'name';
	
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
	public $validSortFields = ['guildID', 'name', 'active'];
}
