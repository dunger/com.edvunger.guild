<?php
namespace guild\data\member;
use guild\data\role\Role;
use guild\system\guild\GuildHandler;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\user\User;
use wcf\data\user\UserAction;
use wcf\data\user\group\UserGroup;
use wcf\system\exception\ErrorException;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class MemberAction extends AbstractDatabaseObjectAction {
    /**
     * @inheritDoc
     */
    public $className = MemberEditor::class;

    /**
     * @inheritDoc
     */
    protected $permissionsCreate = ['admin.guild.canManageMember'];

    /**
     * @inheritDoc
     */
    protected $permissionsDelete = ['admin.guild.canManageMember'];

    /**
     * @inheritDoc
     */
    protected $permissionsUpdate = ['admin.guild.canManageMember'];

    /**
     * @inheritDoc
     */
    protected $requireACP = ['enable', 'disable'];

    /**
     * Validates permissions and parameters.
     */
    public function validateAction() {
        WCF::getSession()->checkPermissions($this->permissionsUpdate);
    }

    /**
     * @inheritDoc
     */
    public function enable() {
        $guildID = (int)$this->parameters['guildID'];
        $memberID = (int)$this->parameters['memberID'];

        if (!$memberID || !$guildID) {
            throw new UserInputException('objectIDs', 'invalid');
        }

        $guild = GuildHandler::getInstance()->getGuild($guildID);
        if ($guild === null) {
            throw new UserInputException('objectIDs', 'invalid');
        }

        $member = Member::getMember($memberID, $guild->guildID);
        if (!$member->memberID) {
            throw new UserInputException('memberID', 'invalid');
        }
        $this->setObjects([$member]);

        $sql = "UPDATE	guild".WCF_N."_member
                    SET	isActive = 1
                    WHERE	memberID = ?
                    AND     guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$member->memberID, $member->guildID]);
    }

    /**
     * @inheritDoc
     */
    public function disable() {
        $guildID = (int)$this->parameters['guildID'];
        $memberID = (int)$this->parameters['memberID'];

        if (!$memberID || !$guildID) {
            throw new UserInputException('objectIDs', 'invalid');
        }

        $guild = GuildHandler::getInstance()->getGuild($guildID);
        if ($guild === null) {
            throw new UserInputException('objectIDs', 'invalid');
        }

        $member = Member::getMember($memberID, $guild->guildID);
        if (!$member->memberID) {
            throw new UserInputException('memberID', 'invalid');
        }
        $this->setObjects([$member]);

        $sql = "UPDATE	guild".WCF_N."_member
                    SET	isActive = 0
                    WHERE	memberID = ?
                    AND     guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$member->memberID, $member->guildID]);
    }

    /**
     * @inheritDoc
     */
    public function setUser() {
        $memberID	= (int)$this->parameters['memberID'];
        $guildID	= (int)$this->parameters['guildID'];
        $username	= $this->parameters['username'];
        $roleID		= (int) $this->parameters['roleID'];
        $groupID	= (int) $this->parameters['groupID'];
        $isMain     = ($this->parameters['isMain'] ? 1 : 0);

        if (!$memberID || empty($username) || !$guildID) {
            throw new UserInputException('username', 'invalid');
        }

        $guild = GuildHandler::getInstance()->getGuild($guildID);
        if ($guild === null) {
            throw new ErrorException(WCF::getLanguage()->get('guild.user.error.guild.notFound'));
        }

        $member = Member::getMember($memberID, $guild->guildID);
        if (!$member->memberID) {
            throw new ErrorException(WCF::getLanguage()->get('guild.user.error.character.notFound'));
        }
        $this->setObjects([$member]);

        $user = User::getUserByUsername($username);
        if (!$user->userID) {
            throw new ErrorException(WCF::getLanguage()->getDynamicVariable('wcf.user.username.error.notFound', ['username' => $username]));
        }

        if ($groupID != 0) {
            $group = UserGroup::getGroupByID($groupID);
            if ($group === null || !$group->groupID) {
                throw new ErrorException(WCF::getLanguage()->getDynamicVariable('guild.acp.member.error.group.notFound'));
            }

            if (!UserGroup::isAccessibleGroup([$group->groupID])) {
                throw new ErrorException(WCF::getLanguage()->get('guild.acp.error.group.permissionDenied'));
            }

            $groupIDs = array_merge([$group->groupID], $user->getGroupIDs());
            $groupIDs = array_unique($groupIDs);
        } else {
            $groupID = null;
        }

        if ($isMain == true) {
            $mainCharacter = Member::getMain($user->userID, $guild->guildID);

            if ($mainCharacter !== false && $mainCharacter->memberID != $memberID) {
                throw new ErrorException('guild.acp.member.error.hasMain');
            }

        }

        $role = new Role($roleID);
        if ($role === null || !$role->isActive || $role->gameID != $guild->gameID) {
            throw new ErrorException(WCF::getLanguage()->get('guild.acp.member.error.role.notFound'));
        }

        $sql = "UPDATE	guild".WCF_N."_member
                    SET	userID = ?,
                        groupID = ?,
                        roleID = ?,
                        isMain = ?
                    WHERE	memberID = ?
                    AND     guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$user->userID, $groupID, $role->roleID, $isMain, $member->memberID, $member->guildID]);

        if ($groupID !== null && !empty($groupIDs)) {
            $action = new UserAction([$user], 'update', [
                'groups' => $groupIDs
            ]);
            $action->executeAction();
        }

        return WCF::getLanguage()->get(($isMain ? 'guild.acp.member.isMain.yes' : 'guild.acp.member.isMain.no')) . ': <a title="' . WCF::getLanguage()->get('wcf.acp.user.edit') . '" href="' . LinkHandler::getInstance()->getLink('UserEdit', ['object' => $user->userID]) . '">' . $user->username . ' [' . WCF::getLanguage()->get($role->name) . ']</a>';
    }

    /**
     * @inheritDoc
     */
    public function deleteUser() {
        $guildID = (int)$this->parameters['guildID'];
        $memberID = (int)$this->parameters['memberID'];

        if (!$memberID || !$guildID) {
            throw new UserInputException('objectIDs', 'invalid');
        }

        $guild = GuildHandler::getInstance()->getGuild($guildID);
        if ($guild === null) {
            throw new UserInputException('objectIDs', 'invalid');
        }

        $member = Member::getMember($memberID, $guild->guildID);
        if (!$member->memberID) {
            throw new ErrorException(WCF::getLanguage()->get('guild.acp.member.error.character.notFound'));
        }
        $this->setObjects([$member]);

        $user = new User($member->userID);
        if (!$user->userID) {
            throw new ErrorException(WCF::getLanguage()->getDynamicVariable('wcf.user.username.error.notFound', ['username' => $member->userID]));
        }

        $groupID = $member->groupID;

        $sql = "UPDATE	guild".WCF_N."_member
                    SET	userID = null,
                        groupID = null,
                        roleID = null,
                        isMain = 0
                    WHERE	memberID = ?
                    AND     guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$member->memberID, $guild->guildID]);

        if ($groupID !== null) {
            $groupsIDs = array_diff($user->getGroupIDs(), [$member->groupID]);
            $action = new UserAction([$user], 'update', [
                'groups' => $groupsIDs
            ]);
            $action->executeAction();
        }
    }
}