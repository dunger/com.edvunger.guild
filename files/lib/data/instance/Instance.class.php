<?php
namespace guild\data\instance;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $instanceID	    unique id of the instance
 * @property-read	integer		    $gameID		    game id to which the instance belongs
 * @property-read	string     	    $name	        name of the instance
 * @property-read	integer		    $encounters	    encounters counter
 * @property-read	integer		    $isActive	    is 1 if the instance is active
 */
class Instance extends DatabaseObject { }