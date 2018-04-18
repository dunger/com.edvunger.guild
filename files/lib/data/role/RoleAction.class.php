<?php
namespace guild\data\role;
use guild\system\role\RoleHandler;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IToggleAction;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class RoleAction extends AbstractDatabaseObjectAction implements IToggleAction {
    /**
     * @inheritDoc
     */
    protected $permissionsCreate = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    protected $permissionsDelete = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    protected $permissionsUpdate = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $className = RoleEditor::class;

    /**
     * @inheritDoc
     */
    public function validateToggle() {
        parent::validateUpdate();
    }

    /**
     * @inheritDoc
     */
    public function toggle() {
        foreach ($this->getObjects() as $role) {
            $role->update(['isActive' => $role->isActive ? 0 : 1]);
        }

        RoleHandler::getInstance()->reloadCache();
    }
}