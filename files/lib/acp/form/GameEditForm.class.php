<?php
namespace guild\acp\form;
use guild\data\game\Game;
use guild\data\game\GameAction;
use guild\data\guild\Guild;
use guild\system\game\GameHandler;
use wcf\form\AbstractForm;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GameEditForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'gameEdit';

    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.game.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * warning id
     * @var	integer
     */
    public $gameID = 0;

    /**
     * edited guild object
     * @var	Guild
     */
    public $game;

    /**
     * @inheritDoc
     */
    public $apiKey = '';

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_POST['apiKey'])) $this->apiKey = StringUtil::trim($_POST['apiKey']);
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        parent::validate();

        if (empty($this->apiKey)) {
            throw new UserInputException('apiKey', 'invalid');
        }
    }

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->gameID = intval($_REQUEST['id']);
        $this->game = new Game($this->gameID);

        if (!$this->game->gameID) {
            throw new PermissionDeniedException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->name = $this->game->name;
        $this->apiKey = $this->game->apiKey;
    }

    /**
     * @inheritDoc
     */
    public function save() {
        AbstractForm::save();

        // update Game
        $this->objectAction = new GameAction([$this->game], 'update', ['data' => [
            'apiKey' => $this->apiKey
        ]]);

        /** @var Game $game */
        $this->objectAction->executeAction()['returnValues'];
        $this->game = new Game($this->gameID);

        GameHandler::getInstance()->reloadCache();

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
            'gameID' => $this->gameID,
            'name' => $this->game->name,
            'apiKey' => $this->game->apiKey
        ]);
    }
}
