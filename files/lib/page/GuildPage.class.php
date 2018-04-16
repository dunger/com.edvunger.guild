<?php
namespace guild\page;
use guild\data\guild\Guild;
use guild\data\member\MemberList;
use guild\data\role\RoleList;
use guild\system\guild\GuildHandler;
use guild\system\role\RoleHandler;
use wcf\page\AbstractPage;
use wcf\system\cache\runtime\UserProfileRuntimeCache;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GuildPage extends AbstractPage {

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.guild.canViewMember'];

    /**
     * event guild id
     * @var	integer
     */
    private $guildID = 0;

    /**
     * guild object
     * @var	Guild
     */
    private $guild = null;

    /**
     * MemberList object
     * @var	MemberList
     */
    private $memberList = null;

    /**
     * RoleList object
     * @var	RoleList
     */
    private $roleList = null;

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['guildID'])) $this->guildID = intval($_REQUEST['guildID']);
        $this->guild = GuildHandler::getInstance()->getGuild($this->guildID);

        if ($this->guild === null) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->memberList = new MemberList();
        $this->memberList->getByGuildID($this->guildID, true);

        $userIDs = [];
        if (!empty($this->memberList->objectIDs)) {
            foreach ($this->memberList->getObjects() as $member) {
                if ($member->userID !== null) {
                    $userIDs[] = $member->userID;
                }
            }
        }

        UserProfileRuntimeCache::getInstance()->getObjects($userIDs);

        $this->roleList = RoleHandler::getInstance()->getRolesByGame($this->guild->getGame()->gameID);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'guild' => $this->guild,
            'memberList' => $this->memberList,
            'roleList' => $this->roleList
        ]);
    }
}