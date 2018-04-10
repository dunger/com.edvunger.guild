<?php
namespace guild\data\wow\instance;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class InstanceAction extends AbstractDatabaseObjectAction {
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
    public $className = InstanceEditor::class;
}