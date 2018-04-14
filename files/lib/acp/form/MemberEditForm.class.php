<?php
namespace guild\acp\form;
use guild\data\guild\Guild;
use guild\data\member\Member;
use wcf\data\user\UserAction;
use wcf\form\AbstractForm;
use wcf\system\cache\runtime\UserRuntimeCache;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class MemberEditForm extends MemberAddForm {
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.guild.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGuild'];

    /**
     * guild id
     * @var	integer
     */
    public $guildID = 0;

    /**
     * member id
     * @var	integer
     */
    public $memberID = 0;

    /**
     * guild object
     * @var	Guild
     */
    public $guild;

    /**
     * edited member object
     * @var	Member
     */
    public $member;

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->guildID = intval($_REQUEST['id']);
        if (isset($_REQUEST['memberID'])) $this->memberID = intval($_REQUEST['memberID']);
        $this->guild = new Guild($this->guildID);

        if (!$this->guild->guildID) {
            throw new IllegalLinkException();
        }

        if ($this->guild->getGame()->apiClass) {
            throw new IllegalLinkException();
        }

        $this->member = Member::getMember($this->memberID, $this->guild->guildID);
        if (!$this->member->memberID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        if ($this->member->userID !== null) {
            $this->user = UserRuntimeCache::getInstance()->getObject($this->member->userID);
        }

        $this->name = $this->member->name;
        $this->thumbnail = $this->member->thumbnail;
        $this->userID = $this->member->userID;
        $this->groupID = $this->member->groupID;
        $this->roleID = $this->member->roleID;
        $this->avatarID = $this->member->avatarID;
        $this->isMain = $this->member->isMain;
        $this->isActive = $this->member->isActive;
        $this->isApiActive = $this->member->isApiActive;
    }

    /**
     * @inheritDoc
     */
    public function save() {
        AbstractForm::save();

        /*
         * no group anymore
         */
        if ($this->groupID == null && $this->member->groupID !== null && $this->member->userID !== null) {
            $action = new UserAction([$this->member->userID], 'removeFromGroups', [
                'groups' => [$this->member->groupID]
            ]);
            $action->executeAction();
        }

        /*
         * new user has a group or current user has now a group
         */
        if ($this->groupID !== null && $this->user !== null && ($this->user->userID != $this->member->userID || $this->member->groupID === null)) {
            $action = new UserAction([$this->user->userID], 'addToGroups', [
                'groups' => [$this->groupID],
                'deleteOldGroups' => false,
                'addDefaultGroups' => false
            ]);
            $action->executeAction();
        }

        /*
         * remove current user from group because there is a new user
         */
        if ($this->member->groupID !== null && $this->user !== null && $this->user->userID != $this->member->userID) {
            $action = new UserAction([$this->member->userID], 'removeFromGroups', [
                'groups' => [$this->member->groupID]
            ]);
            $action->executeAction();
        }

        /*
         * new group for the current user?
         */
        if ($this->member->groupID !== null && $this->groupID !== null && $this->user !== null && $this->user->userID == $this->member->userID && $this->groupID != $this->member->groupID) {
            $action = new UserAction([$this->user->userID], 'removeFromGroups', [
                'groups' => [$this->member->groupID]
            ]);
            $action->executeAction();

            $action = new UserAction([$this->user->userID], 'addToGroups', [
                'groups' => [$this->groupID],
                'deleteOldGroups' => false,
                'addDefaultGroups' => false
            ]);
            $action->executeAction();
        }

        // update member
        $sql = "UPDATE	guild".WCF_N."_member
                    SET	name = ?,
                        userID = ?,
                        groupID = ?,
                        roleID = ?,
                        avatarID = ?,
                        isMain = ?,
                        isActive = ?,
                        isApiActive = 0
                    WHERE	memberID = ?
                    AND     guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([
            $this->name,
            ($this->user === null) ? null : $this->user->userID,
            $this->groupID,
            $this->roleID,
            $this->avatarID,
            $this->isMain,
            $this->isActive ? 1 : 0,
            $this->member->memberID,
            $this->guild->guildID
        ]);

        $this->member = Member::getMember($this->member->memberID, $this->guild->guildID);

        // show success message
        WCF::getTPL()->assign('success', true);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => 'edit',
            'guildID' => $this->guild->guildID,
            'memberID' => $this->member->memberID,
            'guild' => $this->guild,
            'name' => $this->name,
            'user' => $this->user,
            'groupID' => $this->groupID,
            'roleID' => $this->roleID,
            'avatarID' => $this->avatarID,
            'isMain' => $this->isMain,
            'isActive' => $this->isActive,
            'isApiActive' => $this->isApiActive,
            'roleList' => $this->roleList,
            'userGroupList' => $this->userGroupList,
            'avatarList' => $this->avatarList
        ]);
    }
}
