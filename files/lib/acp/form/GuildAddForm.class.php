<?php
namespace guild\acp\form;
use guild\data\game\GameAction;
use guild\data\game\GameList;
use guild\data\guild\GuildAction;
use guild\system\guild\GuildHandler;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\JSON;
use wcf\util\StringUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GuildAddForm extends AbstractForm {
	/**
	 * @inheritDoc
	 */
	public $templateName = 'guildAdd';
	
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'guild.acp.menu.guild.add';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.guild.canManageGuild'];

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
	public $api = '';

    /**
     * @inheritDoc
     */
    public $apiData = [];

    /**
     * @inheritDoc
     */
    public $fieldsError = [];

	/**
	 * @inheritDoc
	 */
	public $isActive = true;
	
	/**
	 * @inheritDoc
	 */
	public $availableGame = null;

	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->availableGame = new GameList();
		$this->availableGame->getActive();
	}
	
	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);
		if (isset($_POST['api'])) $this->api = StringUtil::trim($_POST['api']);
		if (isset($_POST['isActive'])) $this->isActive = ($_POST['isActive'] == 1) ? true : false;
	}
	
	/**
	 * @inheritDoc
	 */
	public function validate() {
		parent::validate();

		$game = $this->availableGame->search($this->api);

        // validate api
        if ($game === null) {
            throw new UserInputException('api', 'invalid');
        }

        $this->gameID = $this->availableGame->search($this->api)->gameID;

        /*
         * no extra fields?
         */
        if (empty($game->apiClass))
        {
            return;
        }

        $gameClass = new $game->apiClass;
        $fields = $gameClass->getFields();

        $this->fieldsError = [];
        foreach ($fields as $name => $data) {
            switch ($data['type']) {
                case 'text':
                    $this->apiData[$name] = StringUtil::trim($_POST[$name]);
                    if (empty($this->apiData[$name]) || preg_match('/'.$data['valid'].'/', $this->apiData[$name]) === false) {
                        $this->fieldsError[] = $name;
                    }

                    break;

                case 'int':
                    $this->apiData[$name] = intval($_POST[$name]);
                    if (!$this->apiData[$name] || preg_match('/'.$data['valid'].'/', $this->apiData[$name]) === false) {
                        $this->fieldsError[] = $name;
                    }

                    break;

                case 'select':
                    $this->apiData[$name] = StringUtil::trim($_POST[$name]);
                    if (empty($this->apiData[$name]) || !in_array($this->apiData[$name], $data['valid'])) {
                        $this->fieldsError[] = $name;
                    }

                    break;
            }
        }

        if (!empty($this->fieldsError)) {
            throw new UserInputException('data', 'invalid');
        }
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();

		// create board
		$this->objectAction = new GuildAction([], 'create', ['data' => [
			'name' => $this->name,
			'gameID' => $this->gameID,
			'apiData' => JSON::encode($this->apiData),
			'isActive' => $this->isActive ? 1 : 0,
		]]);
		/** @var Guild $game */
		$this->objectAction->executeAction()['returnValues'];

		// reset values
		$this->name = $this->api = '';
        $this->gameID = 0;
        $this->fieldsData = [];
		$this->isActive = true;

        GuildHandler::getInstance()->reloadCache();

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();

		$fields = '';
		if ($this->gameID) {
            $gameAction = new GameAction([], '', ['gameID' => $this->gameID]);
            $fields = $gameAction->getFields($this->apiData, $this->fieldsError);
        }

		WCF::getTPL()->assign([
			'action' => 'add',
			'guildID' => 0,
			'name' => $this->name,
			'api' => $this->api,
			'fieldsData' => $fields,
			'isActive' => $this->isActive,
			'availableGame' => $this->availableGame
		]);
	}
}
