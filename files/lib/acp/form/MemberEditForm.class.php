<?php
namespace guild\acp\form;
use guild\data\guild\Guild;
use guild\data\member\Member;
use guild\data\member\MemberAction;
use wcf\form\AbstractForm;
use wcf\system\cache\runtime\UserRuntimeCache;
use wcf\system\exception\PermissionDeniedException;
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
            throw new PermissionDeniedException();
        }

        if ($this->guild->getGame()->apiClass) {
            throw new PermissionDeniedException();
        }

        $this->member = Member::getMember($this->memberID, $this->guild->guildID);
        if (!$this->member->memberID) {
            throw new PermissionDeniedException();
        }

        if ($this->member->userID !== null) {
            $this->user = UserRuntimeCache::getInstance()->getObject($this->member->userID);
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

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

        // create board
        $this->objectAction = new MemberAction([$this->member], 'update', ['data' => [
            'memberID' => $this->member->memberID,
            'guildID' => $this->guild->guildID,
            'name' => $this->name,
            'thumbnail' => '',
            'userID' => ($this->user === null) ? null : $this->user->userID,
            'groupID' => $this->groupID,
            'roleID' => $this->roleID,
            'avatarID' => $this->avatarID,
            'isMain' => $this->isMain ? 1 : 0,
            'isActive' => $this->isActive ? 1 : 0,
            'isApiActive' => $this->isApiActive ? 1 : 0
        ]]);
        /** @var member $member */
        $this->objectAction->executeAction()['returnValues'];

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
