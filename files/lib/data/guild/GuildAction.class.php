<?php
namespace guild\data\guild;
use guild\data\member\MemberAction;
use guild\data\member\MemberList;
use guild\system\guild\GuildHandler;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IToggleAction;
use wcf\data\user\UserAction;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GuildAction extends AbstractDatabaseObjectAction implements IToggleAction {
    /**
     * @inheritDoc
     */
    protected $permissionsCreate = ['admin.guild.canManageGuild'];

    /**
     * @inheritDoc
     */
    protected $permissionsDelete = ['admin.guild.canManageGuild'];

    /**
     * @inheritDoc
     */
    protected $permissionsUpdate = ['admin.guild.canManageGuild'];

	/**
	 * @inheritDoc
	 */
    public $className = GuildEditor::class;

    /**
     * @inheritDoc
     */
    public function validateToggle() {
        parent::validateUpdate();
    }

    /**
     * @inheritDoc
     */
    public function toggle() {
        foreach ($this->getObjects() as $instance) {
            $instance->update(['isActive' => $instance->isActive ? 0 : 1]);
        }

        GuildHandler::getInstance()->reloadCache();
    }

    /**
     * @inheritDoc
     */
    public function delete() {
        if (!empty($this->objectIDs)) {
            foreach ($this->getObjects() as $guild) {
                $members = new MemberList();
                $members->getByGuildID($guild->guildID);

                if (!empty($members->objectIDs)) {
                    foreach ($members->getObjects() as $member) {
                        if ($member->userID !== null) {
                            $action = new UserAction([$member->userID], 'removeFromGroups', [
                                'groups' => [$member->groupID]
                            ]);
                            $action->executeAction();
                        }
                    }
                }
            }
        }

        parent::delete();
    }
}