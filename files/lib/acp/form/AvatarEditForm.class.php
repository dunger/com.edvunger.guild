<?php
namespace guild\acp\form;
use guild\data\avatar\Avatar;
use guild\data\avatar\AvatarAction;
use guild\data\wow\instance\Instance;
use wcf\form\AbstractForm;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class AvatarEditForm extends AvatarAddForm {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.avatar.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * avatar id
     * @var	integer
     */
    public $avatarID = 0;

    /**
     * edited avatar object
     * @var	Avatar
     */
    public $avatar;

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->avatarID = intval($_REQUEST['id']);
        $this->avatar = new Avatar($this->avatarID);

        if (!$this->avatar->avatarID) {
            throw new PermissionDeniedException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->name = $this->avatar->name;
        $this->gameID = $this->avatar->gameID;
        $this->image = $this->avatar->image;
        $this->autoAssignment = $this->avatar->autoAssignment;
        $this->isActive = $this->avatar->isActive;
    }

    /**
     * @inheritDoc
     */
    public function save() {
        AbstractForm::save();

        // update instance
        $this->objectAction = new AvatarAction([$this->avatar], 'update', ['data' => [
            'gameID' => $this->gameID,
            'name' => $this->name,
            'image' => $this->image,
            'autoAssignment' => $this->autoAssignment,
            'isActive' => 1 //$this->isActive ? 1 : 0
        ]]);

        /** @var Instance $instance */
        $this->objectAction->executeAction()['returnValues'];
        $this->avatar = new Avatar($this->avatarID);

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
            'avatarID' => $this->avatarID,
            'name' => $this->avatar->name,
            'gameID' => $this->avatar->gameID,
            'image' => $this->avatar->image,
            'autoAssignment' => $this->avatar->autoAssignment,
            'isActive' => $this->avatar->isActive
        ]);
    }
}
