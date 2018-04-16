<?php
namespace guild\data\member;
use guild\data\avatar\Avatar;
use guild\data\guild\Guild;
use guild\data\role\Role;
use guild\system\avatar\AvatarHandler;
use guild\system\guild\GuildHandler;
use guild\system\role\RoleHandler;
use wcf\data\DatabaseObject;
use wcf\data\user\UserProfile;
use wcf\system\cache\runtime\UserProfileRuntimeCache;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $memberID	    unique id of the member
 * @property-read	integer	        $guildID        id of the guild the member belongs to
 * @property-read	string		    $name		    name of the member
 * @property-read	string		    $thumbnail	    member thumbnail if exists
 * @property-read	integer|null	$userID		    id of the user the member belongs to or `null` if the user does not exist anymore
 * @property-read	integer|null	$groupID	    id of the group the member belongs to or `null` if the group does not exist anymore
 * @property-read	integer|null	$roleID		    id of the role the member belongs to or `null` if the role does not exist anymore
 * @property-read	integer|null	avatarID	    id of the avatar the member belongs to or `null` if the avatar does not exist anymore
 * @property-read	integer		    $isMain	        is 1 if the member is main character
 * @property-read	integer		    $isActive	    is 1 if the member is active
 * @property-read	integer		    $isApiActive	is 1 if the member is active in the api
 */
class Member extends DatabaseObject {
    public $nameNormalize = '';
    public $avatar = null;
    public $guild = null;
    public $profile = null;
    public $role = null;
    public $stats = null;
    public $link = null;

    /**
     * @inheritDoc
     */
    public static function getMember($memberID, $guildID) {
        $sql = "SELECT	member.*
                FROM    guild".WCF_N."_member member
                WHERE   member.memberID = ?
                AND     member.guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$memberID, $guildID]);
        $row = $statement->fetchArray();
        if (!$row) $row = [];

        return new Member(null, $row);
    }

    /**
     * @inheritDoc
     */
    public static function getMemberByUserID($userID, $guildID) {
        $sql = "SELECT	member.*
                FROM    guild".WCF_N."_member member
                WHERE   member.userID = ?
                AND     member.guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$userID, $guildID]);
        $row = $statement->fetchArray();
        if (!$row) $row = [];

        return new Member(null, $row);
    }

    /**
     * @inheritDoc
     */
    public static function getMemberByName($name, $guildID) {
        $sql = "SELECT	member.*
                FROM    guild".WCF_N."_member member
                WHERE   member.name = ?
                AND     member.guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$name, $guildID]);
        $row = $statement->fetchArray();
        if (!$row) $row = [];

        return new Member(null, $row);
    }

    /**
     * @inheritDoc
     */
    public static function getMain($userID, $guildID) {
        $sql = "SELECT	member.*
                FROM    guild".WCF_N."_member member
                WHERE   member.userID = ?
                AND     member.guildID = ?
                AND     member.isMain = 1";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$userID, $guildID]);
        $row = $statement->fetchArray();
        return ($row) ? new Member(null, $row) : false;
    }

    /**
     * @inheritDoc
     */
    public static function getLastMemberID($guildID) {
        $sql = "SELECT	member.memberID
                FROM    guild".WCF_N."_member member
                WHERE   member.guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$guildID]);
        $row = $statement->fetchSingleRow();

        return ($row === false) ? 0 : $row['memberID'];
    }

    /**
     * Returns the avatar.
     *
     * @return	Avatar
     */
    public function getAvatar() {
        if ($this->avatar === null && $this->avatarID) {
            $this->avatar = AvatarHandler::getInstance()->getAvatar($this->avatarID);
        }

        return $this->avatar;
    }

    /**
     * Returns the UserProfiöle.
     *
     * @return	UserProfile
     */
    public function getUserProfile() {
        if ($this->profile === null && $this->userID) {
            $this->profile =  UserProfileRuntimeCache::getInstance()->getObject($this->userID);
        }

        return $this->profile;
    }

    /**
     * Returns the guild
     *
     * @return	Guild
     */
    public function getGuild() {
        if ($this->guild === null && $this->guildID) {
            $this->guild = GuildHandler::getInstance()->getGuild($this->guildID);
        }

        return $this->guild;
    }

    /**
     * Returns the role
     *
     * @return	Role
     */
    public function getRole() {
        if ($this->role === null && $this->roleID) {
            $this->role = RoleHandler::getInstance()->getRole($this->roleID);
        }

        return $this->role;
    }

    /**
     * @inheritDoc
     */
    public function getStats($key, $short=false, $round=false, $default=0) {
        if (!isset($this->stats['this'][$key]) && !isset($this->stats['last'][$key])) {
            return $default;
        } else if (!isset($this->stats['this'][$key]) && isset($this->stats['last'][$key])) {
            $ret = $this->stats['last'][$key];
        } else {
            $ret = $this->stats['this'][$key];
        }

        if ($short) {
            $ret = StringUtil::getShortUnit($ret);
        }

        if ($round) {
            $ret = round($ret, 2);
        }

        return $ret;
    }

    /**
     * @inheritDoc
     */
    public function getStatsDiff($key, $short=false, $round=false) {
        $value = 0;
        if (isset($this->stats['this'][$key]) && isset($this->stats['last'][$key])) {
            $value = $this->stats['this'][$key] - $this->stats['last'][$key];
        }

        if ($short) {
            $value = StringUtil::getShortUnit($value);
        }

        if ($round) {
            $value = round($value, 2);
        }

        return ($value <= 0) ? $value : '+' . $value;
    }

    /**
     * @inheritDoc
     */
    public function getTime($key) {
        if (isset($this->stats['this'][$key])) {
            return substr((string) $this->stats['this'][$key], 0, -3);
        } else if (isset($this->stats['last'][$key])) {
            return substr((string) $this->stats['last'][$key], 0, -3);
        } else {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function buildLink() {
        if ($this->link === null && !empty($this->getGuild()->getGame()->apiClass)) {
            $this->link = LinkHandler::getInstance()->getLink($this->getGuild()->getSlugTitle(), ['application' => 'guild', 'id' => $this->memberID, 'title' => $this->name]);
        }

        return $this->link;
    }
}