<?php
namespace guild\system\event\listener;
use guild\data\guild\Guild;
use guild\data\guild\GuildList;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */

class CalendarCategoryAddFormListener implements IParameterizedEventListener {
    public $guildList = null;

    /**
     * birthday of the created or edited person
     * @var	string
     */
    protected $guildID = 0;

    /**
     * @see	IPage::assignVariables()
     */
    protected function assignVariables() {
        WCF::getTPL()->assign([
            'guildList' => $this->guildList,
            'guildID' => $this->guildID
        ]);
    }

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters) {
        if (method_exists($this, $eventName) && $eventName !== 'execute') {
            $this->$eventName($eventObj);
        }
        else {
            throw new \LogicException('Unreachable');
        }
    }

    /**
     * @see	IPage::readData()
     */
    protected function readData($form) {
        $this->guildList = new GuildList();
        $this->guildList->getActive();
    }

    /**
     * @see	IForm::readFormParameters()
     */
    protected function readFormParameters() {
        if (isset($_POST['guildID'])) {
            $this->guildID = (int) $_POST['guildID'];
        }
    }

    /**
     * @see	IForm::save()
     */
    protected function save($form) {
        $form->additionalData['guildID'] = $this->guildID;
    }

    /**
     * @see	IForm::saved()
     */
    protected function saved() {
        // missing add | edit
        //$this->guildID = 0;
    }

    /**
     * @see	IForm::validate()
     */
    protected function validate() {
        if (empty($this->guildID)) {
            return;
        }

        $guild = new Guild($this->guildID);
        if ($guild === null || $guild->isActive == 0) {
            throw new UserInputException('guildID', 'noValidSelection');
        }
    }
}