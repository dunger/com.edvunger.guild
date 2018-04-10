<?php
namespace guild\page;
use wcf\page\AbstractPage;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class MemberPage extends AbstractPage {

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.guild.canViewMember'];

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();
    }
}