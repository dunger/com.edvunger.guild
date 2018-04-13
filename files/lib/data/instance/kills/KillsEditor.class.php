<?php
namespace guild\data\instance\kills;
use wcf\data\DatabaseObjectEditor;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class KillsEditor extends DatabaseObjectEditor {
    protected static $baseClass = Kills::class;
}