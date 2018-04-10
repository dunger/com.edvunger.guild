<?php
namespace guild\acp\page;
use guild\data\game\GameList;
use wcf\page\SortablePage;
use wcf\system\event\EventHandler;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GameListPage extends SortablePage {
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
    public $objectListClassName = GameList::class;

    /**
     * @inheritDoc
     */
    public $defaultSortField = 'gameID';

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
    public $validSortFields = ['gameID', 'name'];

    /**
     * @inheritDoc
     */
    protected function initObjectList() {
        parent::initObjectList();

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
    }
}