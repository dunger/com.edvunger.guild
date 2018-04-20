<?php
namespace guild\acp\form;
use guild\data\game\Game;
use guild\data\game\GameAction;
use guild\system\guild\GuildHandler;
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
class GameAddForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'gameAdd';

    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.game.add';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $name = '';

    /**
     * @inheritDoc
     */
    public $apiClass = '';

    /**
     * @inheritDoc
     */
    public $apiKey = '';

    /**
     * @inheritDoc
     */
    public $apiData = [
        'default' => '',
        'wow' => 'guild\\system\\game\\api\\wow\\WoW'
    ];

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);
        if (isset($_POST['apiClass'])) $this->apiClass = StringUtil::trim($_POST['apiClass']);
        if (isset($_POST['apiKey'])) $this->apiKey = StringUtil::trim($_POST['apiKey']);
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        parent::validate();

        if (empty($this->name)) {
            throw new UserInputException('name', 'invalid');
        }

        $game = Game::getGameByName($this->name);
        if ((!isset($this->game) && $game->gameID) || (isset($this->game) && $game->gameID !== 0 && $game->gameID != $this->game->gameID)) {
            throw new UserInputException('name', 'inUse');
        }

        if (array_key_exists($this->apiClass, $this->apiData) === false && $this->game === null) {
            throw new UserInputException('apiClass', 'notFound');
        }
    }

    /**
     * @inheritDoc
     */
    public function save() {
        parent::save();

        // create game
        $this->objectAction = new GameAction([], 'create', ['data' => [
            'name' => $this->name,
            'apiClass' => $this->apiData[$this->apiClass],
            'apiKey' => $this->apiKey,
            'detailsPage' => '',
            'detailsMemberPage' => '',
            'isActive' => 1,
        ]]);
        /** @var Game $game */
        $this->objectAction->executeAction()['returnValues'];

        // reset values
        $this->name = $this->apiClass = $this->apiKey = '';

        GuildHandler::getInstance()->reloadCache();

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
            'guildID' => 0,
            'name' => $this->name,
            'apiClass' => $this->apiClass,
            'apiKey' => $this->apiKey,
        ]);
    }
}
