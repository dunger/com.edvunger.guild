<?php
namespace guild\acp\page;
use guild\data\member\MemberList;
use guild\data\game\Game;
use guild\data\guild\Guild;
use guild\data\role\RoleList;
use wcf\data\user\group\UserGroup;
use wcf\data\user\group\UserGroupList;
use wcf\system\event\EventHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;
use wcf\page\SortablePage;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class MemberListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'guild.acp.menu.guild.list';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.guild.canManageMember'];
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = MemberList::class;
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortField = 'isApiActive';
	
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
	public $validSortFields = ['memberID', 'name', 'userID', 'isActive', 'isApiActive'];
	
	/**
	 * new raidRoles
	 * @var	array
	 */
	public $rankList = [];
	
	/**
	 * new raidRoles
	 * @var	array
	 */
	public $roleList = [];
	
	/**
	 * new userGroupList
	 * @var	array
	 */
	public $userGroupList = [];

    /**
     * warning id
     * @var	integer
     */
    public $guildID = 0;

    /**
     * edited guild object
     * @var	Guild
     */
    public $guild;

    /**
     * edited game object
     * @var	Game
     */
    public $game;
	
	/**
	 * @inheritDoc
	 */
	protected function initObjectList() {
		parent::initObjectList();

        if (isset($_REQUEST['id'])) $this->guildID = intval($_REQUEST['id']);
        $this->guild = new Guild($this->guildID);

        if (!$this->guild->guildID) {
            throw new PermissionDeniedException();
        }

        $this->objectList->getConditionBuilder()->add('guildID = ?', [$this->guild->guildID]);

		// call validateSortField event
		EventHandler::getInstance()->fireAction($this, 'validateSortField');
		
		if (!in_array($this->sortField, $this->validSortFields)) {
			$this->sortField = $this->defaultSortField;
		}
		// call validateSortOrder event
		EventHandler::getInstance()->fireAction($this, 'validateSortOrder');
		
		switch ($this->sortOrder) {
			case 'ASC':
			case 'DESC':
			break;
			
			default:
				$this->sortOrder = $this->defaultSortOrder;
		}
		
		if ($this->sortField != 'name') {
			$this->sqlOrderBy = $this->sortField . " " . $this->sortOrder . ", name ASC";
			$this->sortField = $this->sortOrder = '';
		}
	}
	
	/**
	 * @inheritDoc
	 */
	protected function readObjects() {
		parent::readObjects();

        $this->game = new Game($this->guild->gameID);
		
		$this->roleList = new RoleList();
		$this->roleList->getActive($this->guild->gameID);
		
		$this->userGroupList = new UserGroupList();
		$this->userGroupList->getConditionBuilder()->add('groupType NOT IN (?)', [[UserGroup::GUESTS, UserGroup::EVERYONE, UserGroup::USERS]]);
		$this->userGroupList->readObjects();
	}
	
	/**
	 * @inheritDoc
	 */
	public function readData() {
		parent::readData();
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
		    'editable'      => (empty($this->game->apiClass)) ? true : false,
		    'guild'         => $this->guild,
			'roleList'		=> $this->roleList,
			'userGroupList' => $this->userGroupList
		]);
	}
}
