<?php
namespace guild\acp\form;
use guild\data\role\Role;
use guild\data\role\RoleAction;
use guild\system\role\RoleHandler;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class RoleEditForm extends RoleAddForm {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.role.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * role id
     * @var	integer
     */
    public $roleID = 0;

    /**
     * edited role object
     * @var	Role
     */
    public $role;

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->roleID = intval($_REQUEST['id']);
        $this->role = new Role($this->roleID);

        if (!$this->role->roleID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->gameID = $this->role->gameID;
        $this->name = $this->role->name;
    }

    /**
     * @inheritDoc
     */
    public function save() {
        AbstractForm::save();

        // update instance
        $this->objectAction = new RoleAction([$this->role], 'update', ['data' => [
            'gameID' => $this->gameID,
            'name' => $this->name,
            'isActive' => 1 //$this->isActive ? 1 : 0
        ]]);

        /** @var Instance $instance */
        $this->objectAction->executeAction()['returnValues'];
        $this->role = new Role($this->roleID);

        RoleHandler::getInstance()->reloadCache();

        // show success message
        WCF::getTPL()->assign('success', true);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => 'edit',
            'roleID' => $this->roleID,
            'gameID' => $this->role->gameID,
            'name' => $this->role->name,
        ]);
    }
}
