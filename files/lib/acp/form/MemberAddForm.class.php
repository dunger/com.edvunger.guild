<?php
namespace guild\acp\form;
use guild\data\avatar\Avatar;
use guild\data\avatar\AvatarList;
use guild\data\guild\Guild;
use guild\data\member\Member;
use guild\data\member\MemberAction;
use guild\data\role\Role;
use guild\data\role\RoleList;
use wcf\data\user\group\UserGroup;
use wcf\data\user\group\UserGroupList;
use wcf\data\user\User;
use wcf\data\user\UserAction;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class MemberAddForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $templateName = 'memberAdd';

    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'guild.acp.menu.guild.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.guild.canManageGames'];

    /**
     * @inheritDoc
     */
    public $guildID = 0;

    /**
     * @inheritDoc
     */
    public $memberID = 0;

    /**
     * @inheritDoc
     */
    public $name = '';

    /**
     * @inheritDoc
     */
    public $username = '';

    /**
     * @inheritDoc
     */
    public $roleID = '';

    /**
     * @inheritDoc
     */
    public $avatarID = '';

    /**
     * @inheritDoc
     */
    public $groupID = 0;

    /**
     * @inheritDoc
     */
    public $isMain = true;

    /**
     * @inheritDoc
     */
    public $isActive = true;

    /**
     * @inheritDoc
     */
    public $isApiActive = true;

    /**
     * @inheritDoc
     */
    public $guild = null;

    /**
     * @inheritDoc
     */
    public $user = null;

    /**
     * @inheritDoc
     */
    public $roleList = null;

    /**
     * @inheritDoc
     */
    public $userGroupList = null;

    /**
     * @inheritDoc
     */
    public $avatarList = null;

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->guildID = intval($_REQUEST['id']);
        $this->guild = new Guild($this->guildID);

        if (!$this->guild->guildID) {
            throw new IllegalLinkException();
        }

        if ($this->guild->getGame()->apiClass) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_POST['memberID'])) $this->memberID = intval($_POST['memberID']);
        if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);
        if (isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
        if (isset($_POST['roleID'])) $this->roleID = intval($_POST['roleID']);
        if (isset($_POST['avatarID'])) $this->avatarID = intval($_POST['avatarID']);
        if (isset($_POST['groupID'])) $this->groupID = intval($_POST['groupID']);
        if (isset($_POST['isMain'])) $this->isMain = ($_POST['isMain'] == 1) ? true : false;
        if (isset($_POST['isActive'])) $this->isActive = ($_POST['isActive'] == 1) ? true : false;
        if (isset($_POST['isApiActive'])) $this->isApiActive = ($_POST['isApiActive'] == 1) ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->roleList = new RoleList();
        $this->roleList->getActive($this->guild->gameID);

        $this->userGroupList = new UserGroupList();
        $this->userGroupList->getConditionBuilder()->add('groupType NOT IN (?)', [[UserGroup::GUESTS, UserGroup::EVERYONE, UserGroup::USERS]]);
        $this->userGroupList->readObjects();

        $this->avatarList = new AvatarList();
        $this->avatarList->getActiveByGame($this->guild->gameID);
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        parent::validate();

        if (isset($_REQUEST['id'])) $this->guildID = intval($_REQUEST['id']);
        $this->guild = new Guild($this->guildID);

        if (!$this->guild->guildID) {
            throw new PermissionDeniedException();
        }

        if ($this->guild->getGame()->apiClass) {
            throw new PermissionDeniedException();
        }

        $member = Member::getMemberByName($this->name, $this->guild->guildID);
        if ($member->memberID) {
            if (!isset($this->member) || $this->member->name != $this->name) {
                throw new UserInputException('name', 'invalid');
                //throw new ErrorException(WCF::getLanguage()->get('guild.user.error.character.found'));
            }
        }

        if (!empty($this->username)) {
            $this->user = User::getUserByUsername($this->username);
            if (!$this->user->userID) {
                throw new UserInputException('username', 'invalid');
                //throw new ErrorException(WCF::getLanguage()->getDynamicVariable('wcf.user.username.error.notFound', ['username' => $this->username]));
            }

            if ($this->isMain == true) {
                $mainCharacter = Member::getMain($this->user->userID, $this->guild->guildID);

                if ($mainCharacter !== false && $mainCharacter->memberID != $this->memberID) {
                    throw new UserInputException('isMain', 'invalid');
                    //throw new ErrorException('guild.acp.member.error.hasMain');
                }
            }

            if ($this->groupID != 0) {
                $group = UserGroup::getGroupByID($this->groupID);
                if ($group === null || !$group->groupID) {
                    throw new UserInputException('groupID', 'invalid');
                    //throw new ErrorException(WCF::getLanguage()->getDynamicVariable('guild.acp.member.error.group.notFound'));
                }

                if (!UserGroup::isAccessibleGroup([$group->groupID])) {
                    throw new UserInputException('groupID', 'invalid');
                    //throw new ErrorException(WCF::getLanguage()->get('guild.acp.error.group.permissionDenied'));
                }
            } else {
                $this->groupID = null;
            }
        } else {
            $this->user = null;
            $this->groupID = null;
        }

        $role = new Role($this->roleID);
        if ($role === null || !$role->isActive || $role->gameID != $this->guild->gameID) {
            throw new UserInputException('roleID', 'invalid');
            //throw new ErrorException(WCF::getLanguage()->get('guild.acp.member.error.role.notFound'));
        }

        $avatar = new Avatar($this->avatarID);
        if ($avatar === null || !$avatar->isActive || $avatar->gameID != $this->guild->gameID) {
            throw new UserInputException('avatarID', 'invalid');
            //throw new ErrorException(WCF::getLanguage()->get('guild.acp.member.error.avatar.notFound'));
        }
    }

    /**
     * @inheritDoc
     */
    public function save() {
        parent::save();

        $lastMemberID = Member::getLastMemberID($this->guildID);

        // create instance
        $this->objectAction = new MemberAction([], 'create', ['data' => [
            'memberID' => $lastMemberID + 1,
            'guildID' => $this->guildID,
            'name' => $this->name,
            'thumbnail' => '',
            'userID' => ($this->user === null) ? null : $this->user->userID,
            'groupID' => $this->groupID,
            'roleID' => $this->roleID,
            'avatarID' => $this->avatarID,
            'isMain' => $this->isMain ? 1 : 0,
            'isActive' => $this->isActive ? 1 : 0,
            'isApiActive' => 0
        ]]);
        /** @var Member $member */
        $this->objectAction->executeAction()['returnValues'];

        if ($this->groupID !== null && $this->user !== null) {
            $action = new UserAction([$this->user->userID], 'addToGroups', [
                'groups' => [$this->groupID],
                'deleteOldGroups' => false,
                'addDefaultGroups' => false
            ]);
            $action->executeAction();
        }

        // reset values
        $this->name = $this->title = '';
        $this->memberID = $this->guildID = $this->userID = $this->groupID = $this->roleID = $this->avatarID = 0;
        $this->isMain = $this->isActive = $this->isApiActive = true;
        $this->user = null;

        // show success message
        WCF::getTPL()->assign('success', true);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => 'add',
            'memberID' => 0,
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