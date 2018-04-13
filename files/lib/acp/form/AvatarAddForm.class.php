<?php
namespace guild\acp\form;
use guild\data\avatar\Avatar;
use guild\data\avatar\AvatarAction;
use guild\system\avatar\AvatarHandler;
use guild\system\game\GameHandler;
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
class AvatarAddForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'avatarAdd';

    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.avatar.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $avatarID = 0;

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
    public $image = '';

    /**
     * @inheritDoc
     */
    public $autoAssignment = 0;

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
        if (isset($_POST['image'])) $this->image = StringUtil::trim($_POST['image']);
        if (isset($_POST['autoAssignment'])) $this->autoAssignment = intval($_POST['autoAssignment']);
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

        if (empty($this->image)) {
            throw new UserInputException('image', 'invalid');
        }

        $game = GameHandler::getInstance()->getGame($this->gameID);
        if ($game == null) {
            throw new UserInputException('gameID', 'invalid');
        }
    }

    /**
     * @inheritDoc
     */
    public function save() {
        parent::save();

        // create instance
        $this->objectAction = new AvatarAction([], 'create', ['data' => [
            'gameID' => $this->gameID,
            'name' => $this->name,
            'image' => $this->image,
            'autoAssignment' => $this->autoAssignment,
            'isActive' => $this->isActive ? 1 : 0
        ]]);
        /** @var Avatar $avatar */
        $this->objectAction->executeAction()['returnValues'];

        AvatarHandler::getInstance()->reloadCache();

        // reset values
        $this->name = $this->image = '';
        $this->gameID = $this->autoAssignment = 0;
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
            'avatarID' => 0,
            'name' => $this->name,
            'gameID' => $this->gameID,
            'image' => $this->image,
            'autoAssignment' => $this->autoAssignment,
            'isActive' => $this->isActive,
            'gameList' => $this->gameList
        ]);
    }
}