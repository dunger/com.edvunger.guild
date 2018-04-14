<?php
namespace guild\data\wow\statistic;
use wcf\data\DatabaseObject;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $statisticID	    unique id of the statistic
 * @property-read	integer     	$memberID	        member id to which the statistic belongs
 * @property-read	string		    $guildID	        guild id to which the statistic belongs
 * @property-read	string	        $week		        date of the statistic
 * @property-read	string          $typeID	            type id to which the statistic belongs
 * @property-read	string          $value	            value of the statistic
 */
class Statistic extends DatabaseObject {}