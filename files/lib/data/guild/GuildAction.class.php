<?php
namespace guild\data\guild;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GuildAction extends AbstractDatabaseObjectAction {
	/**
	 * @inheritDoc
	 */
    public $className = GuildEditor::class;
}