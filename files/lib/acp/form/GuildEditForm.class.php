<?php
namespace guild\acp\form;
use guild\data\game\GameAction;
use guild\data\guild\Guild;
use guild\data\guild\GuildAction;
use guild\system\guild\GuildHandler;
use wcf\form\AbstractForm;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;
use wcf\util\JSON;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GuildEditForm extends GuildAddForm {
	/**
	 * @inheritDoc
	 */
    public $activeMenuItem = 'guild.acp.menu.guild.list';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.guild.canManageGuild'];
	
	/**
	 * warning id
	 * @var	integer
	 */
	public $guildID = 0;

    /**
     * edited guild object
     * @var	Guild
     */
    public $guild;
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();

        if (isset($_REQUEST['id'])) $this->guildID = intval($_REQUEST['id']);
        $this->guild = new Guild($this->guildID);

        if (!$this->guild->guildID) {
            throw new PermissionDeniedException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->name = $this->guild->name;
        $this->api = $this->guild->api;
        $this->apiData = JSON::decode($this->guild->apiData);
        $this->isActive = $this->guild->isActive;
    }
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		AbstractForm::save();

        // create board
        $this->objectAction = new GuildAction([$this->guild], 'update', ['data' => [
            'name' => $this->name,
            'gameID' => $this->availableGame->search($this->api)->gameID,
            'apiData' => JSON::encode($this->apiData),
            'isActive' => $this->isActive ? 1 : 0,
        ]]);
        /** @var Guild $game */
        $this->objectAction->executeAction()['returnValues'];

        GuildHandler::getInstance()->reloadCache();

        // show success message
        WCF::getTPL()->assign('success', true);
    }

	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();

        $gameAction = new GameAction([], '', ['gameID' => $this->guild->gameID]);
        $fields = $gameAction->getFields($this->apiData, $this->fieldsError);

		WCF::getTPL()->assign([
			'action' => 'edit',
			'guildID' => $this->guildID,
            'api' => $this->guild->gameID,
			'guild' => $this->guild,
			'fieldsData' => $fields
		]);
	}
}
