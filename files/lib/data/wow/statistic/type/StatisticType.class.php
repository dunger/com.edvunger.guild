<?php
namespace guild\data\wow\statistic\type;
use wcf\data\DatabaseObject;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $typeID	        unique id of the statistic
 * @property-read	string		    $type		    type of the statistic
 * @property-read	string		    $value		    value of the statistic
 * @property-read	string		    $value2		    value2 of the statistic
 * @property-read	string		    $description	description of the statistic
 */
class StatisticType extends DatabaseObject {}