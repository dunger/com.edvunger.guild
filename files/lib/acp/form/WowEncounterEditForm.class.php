<?php
namespace guild\acp\form;
use guild\data\wow\encounter\Encounter;
use guild\data\wow\encounter\EncounterAction;
use guild\data\wow\instance\Instance;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowEncounterEditForm extends WowEncounterAddForm {
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
    public $encounterID = 0;

    /**
     * edited encounter object
     * @var	Encounter
     */
    public $encounter;

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->encounterID = intval($_REQUEST['id']);
        $this->encounter = new Encounter($this->encounterID);

        if (!$this->encounter->encounterID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->objectAction = $this->encounter->objectAction;
        $this->name = $this->encounter->name;
        $this->instanceID = $this->encounter->instanceID;
    }

    /**
     * @inheritDoc
     */
    public function save() {
        AbstractForm::save();

        // update encounter
        $this->objectAction = new EncounterAction([$this->encounter], 'update', ['data' => [
            'encounterID' => $this->encounterID,
            'name' => $this->name,
            'instanceID' => $this->instanceID
        ]]);

        /** @var Instance $instance */
        $this->objectAction->executeAction()['returnValues'];
        $this->encounter = new Encounter($this->encounterID);

        // show success message
        WCF::getTPL()->assign('success', true);

        /*
         * redirect to the new url
         */
        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('WowEncounterEdit', ['application' => 'guild', 'id' => $this->encounterID, 'isACP' => true]));
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => 'edit',
            'encounterID' => $this->encounterID,
            'instanceID' => $this->instanceID,
            'name' => $this->encounter->name
        ]);
    }
}
