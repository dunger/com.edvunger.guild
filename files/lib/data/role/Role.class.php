<?php
namespace guild\data\role;
use wcf\data\DatabaseObject;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $roleID	    unique id of the role
 * @property-read	integer     	$gameID     id of the game the role belongs to
 * @property-read	string		    $name		name of the role
 * @property-read	integer		    $isActive	is 1 if the role is active
 */
class Role extends DatabaseObject {}