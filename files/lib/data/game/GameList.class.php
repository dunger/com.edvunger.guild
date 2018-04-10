<?php
namespace guild\data\game;
use wcf\data\DatabaseObjectList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GameList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
	public $className = Game::class;
	
	/**
	 * @inheritDoc
	 */
	public function getActive() {
		parent::getConditionBuilder()->add('isActive = 1', []);
		parent::readObjects();
	 }
}