<?php
namespace guild\form;
use guild\data\member\Member;
use guild\data\member\MemberList;
use guild\data\role\RoleList;
use guild\system\guild\GuildHandler;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;

/**
 * Shows the guild role edit form.
 *
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class UserRoleEditForm extends AbstractForm {
    /**
     * @inheritDoc
     */
    public $memberRoles = [];

	/**
	 * new raidMembers
	 * @var	array
	 */
	public $memberList = [];


	/**
	 * @inheritDoc
	 */
	public $loginRequired = true;

	/**
	 * @inheritDoc
	 */
	public $templateName = 'userProfileRoleEdit';

	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();

		$this->memberList = new MemberList();
		$this->memberList->getActiveByUserID(WCF::getUser()->userID);

		$roleList = new RoleList();
		$this->roleList = $roleList->getActiveSortByGame();

		if (isset($_POST['memberRoles']) && is_array($_POST['memberRoles'])) $this->memberRoles = $_POST['memberRoles'];
	}
	
	/**
	 * @inheritDoc
	 */
	public function validate() {
		AbstractForm::validate();

		foreach ($this->memberRoles as $memberID => $roleID) {
            $values = explode('|', $memberID);

		    if (sizeof($values) != 2) {
                throw new UserInputException($memberID, 'invalid');
            }

		    $guildID = (int)$values[0];
            $memberID = (int)$values[1];
		    $roleID = (int)$roleID;

		    $guild = GuildHandler::getInstance()->getGuild($guildID);
            if ($guild === null) {
                throw new UserInputException($memberID, 'invalid');
            }

            $member = Member::getMember($memberID, $guildID);
		    if (!$member->memberID) {
                throw new UserInputException($memberID, 'invalid');
            }

            if ($member->userID != WCF::getUser()->userID) {
                throw new UserInputException($memberID, 'invalid');
            }

            if (!$roleID) {
                throw new UserInputException($memberID, 'empty');
            }

            $roles = $member->getGuild()->getRoles();
            if (!isset($roles[$roleID]) || $roles[$roleID]->gameID != $member->getGuild()->gameID) {
                throw new UserInputException($memberID, 'invalid');
            }
        }
	}
	
	/**
	 * @inheritDoc
	 */
	public function readData() {
		parent::readData();
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();

		$members = [];
		if (!empty($this->memberList->objectIDs)) {
            foreach ($this->memberList->getObjects() as $member) {
                if (!isset($members[$member->guildID])) {
                    $members[$member->guildID]['guild'] = GuildHandler::getInstance()->getGuild($member->guildID);
                }
                $members[$member->guildID]['objects'][$member->memberID] = $member;
                /*
                 * not the best way, but it works :/
                 */
                $members[$member->guildID]['objects'][$member->memberID]->nameNormalize = $member->guildID . "|" . $member->memberID;
            }
        }

		WCF::getTPL()->assign([
			'memberList'	=> $members,
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function show() {
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.profile.calendarRaid');
		
		parent::show();
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();

        foreach ($this->memberRoles as $memberID => $roleID) {
            $values = explode('|', $memberID);
            $guildID = (int)$values[0];
            $memberID = (int)$values[1];
            $roleID = (int)$roleID;

            $sql = "UPDATE	guild".WCF_N."_member
                    SET	roleID = ?
                    WHERE	memberID = ?
                    AND     guildID = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute([$roleID, $memberID, $guildID]);
        }

        $this->memberList->getActiveByUserID(WCF::getUser()->userID);
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
}
