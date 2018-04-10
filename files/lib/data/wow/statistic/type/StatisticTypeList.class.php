<?php
namespace guild\data\wow\statistic\type;
use wcf\data\DatabaseObjectList;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class StatisticTypeList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
    public $className = StatisticType::class;
}