<?php
namespace guild\data\avatar;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class AvatarAction extends AbstractDatabaseObjectAction {
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
    public $className = AvatarEditor::class;
}