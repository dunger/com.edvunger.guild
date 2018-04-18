<?php
namespace guild\acp\form;
use guild\data\role\Role;
use guild\data\role\RoleAction;
use guild\system\game\GameHandler;
use guild\system\role\RoleHandler;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class RoleAddForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'roleAdd';

    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.role.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $roleID = 0;

    /**
     * @inheritDoc
     */
    public $gameID = 0;

    /**
     * @inheritDoc
     */
    public $name = '';

    /**
     * @inheritDoc
     */
    public $isActive = true;

    /**
     * @inheritDoc
     */
    public $gameList = null;

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);
        if (isset($_POST['gameID'])) $this->gameID = intval($_POST['gameID']);
        if (isset($_POST['isActive'])) $this->isActive = ($_POST['isActive'] == 1) ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->gameList = GameHandler::getInstance()->getGames();
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        parent::validate();

        if (empty($this->name)) {
            throw new UserInputException('name', 'invalid');
        }

        $game = GameHandler::getInstance()->getGame($this->gameID);
        if ($game == null) {
            throw new UserInputException('gameID', 'invalid');
        }

        $roles = RoleHandler::getInstance()->getRolesByGame($this->gameID);

        if ($roles !== null && count($roles) > 4) {
            throw new UserInputException('gameID', 'invalid');
        }

    }

    /**
     * @inheritDoc
     */
    public function save() {
        parent::save();

        // create instance
        $this->objectAction = new RoleAction([], 'create', ['data' => [
            'gameID' => $this->gameID,
            'name' => $this->name,
            'isActive' => 1 //$this->isActive ? 1 : 0
        ]]);
        /** @var Role $role */
        $this->objectAction->executeAction()['returnValues'];

        RoleHandler::getInstance()->reloadCache();

        // reset values
        $this->name = '';
        $this->gameID = 0;
        $this->isActive = true;

        // show success message
        WCF::getTPL()->assign('success', true);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => 'add',
            'roleID' => 0,
            'gameID' => $this->gameID,
            'name' => $this->name,
            'isActive' => $this->isActive,
            'gameList' => $this->gameList
        ]);
    }
}