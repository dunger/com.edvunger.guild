<?php
namespace guild\acp\page;
use wcf\page\AbstractPage;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowStatisticPage extends AbstractPage {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.game.wow';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();
    }

}