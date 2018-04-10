<?php
namespace guild\data\wow\statistic;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class StatisticAction extends AbstractDatabaseObjectAction {
	/**
	 * @inheritDoc
	 */
    public $className = StatisticEditor::class;
}