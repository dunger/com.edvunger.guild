<?php
namespace guild\acp\page;
use guild\data\game\Game;
use guild\data\instance\InstanceList;
use wcf\page\SortablePage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceListPage extends SortablePage {
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
    public $validSortFields = ['instanceID', 'name', 'encounters'];

    /**
     * @inheritDoc
     */
    public $gameID = 0;

    /**
     * @inheritDoc
     */
    public $game = null;

    /**
     * @inheritDoc
     */
    protected function initObjectList() {
        parent::initObjectList();

        if (isset($_REQUEST['id'])) $this->gameID = intval($_REQUEST['id']);
        $this->game = new Game($this->gameID);

        if (!$this->game->gameID) {
            throw new IllegalLinkException();
        }

        $this->objectList->getConditionBuilder()->add('gameID = ?', [$this->game->gameID]);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'gameID' => $this->gameID
        ]);
    }
}