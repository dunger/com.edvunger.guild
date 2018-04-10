<?php
namespace guild\page;
use guild\data\game\Game;
use guild\data\guild\Guild;
use guild\data\guild\ViewableGuild;
use guild\data\member\MemberList;
use guild\system\cache\runtime\ViewableGuildRuntimeCache;
use guild\system\guild\GuildHandler;
use wcf\page\AbstractPage;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowPage extends AbstractPage {
    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.guild.canViewMember'];

    /**
     * event guild id
     * @var	integer
     */
    public $guildID = 0;

    /**
     * guild object
     * @var	Guild
     */
    public $guild;

    /**
     * game object
     * @var	Game
     */
    public $game;

    /**
     * shown sortFields
     * @var	array
     */
    public $sortFields = ['name', 'role', 'gear', 'al', 'ap'];

    /**
     * shown sortFields
     * @var	string
     */

    public $sortField = 'name';

    /**
     * shown sortOrder
     * @var	string
     */

    public $sortOrder = 'ASC';

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['guildID'])) $this->guildID = intval($_REQUEST['guildID']);
        $this->guild = GuildHandler::getInstance()->getGuild($this->guildID);

        if (!$this->guild->guildID) {
            throw new PermissionDeniedException();
        }

        if (isset($_GET['sortField']) && in_array($_GET['sortField'], $this->sortFields)) {
            $this->sortField = StringUtil::trim($_GET['sortField']);
        }

        if (isset($_GET['sortOrder']) && in_array(strtoupper($_GET['sortOrder']), ['ASC', 'DESC'])) {
            $this->sortOrder = strtoupper(StringUtil::trim($_GET['sortOrder']));
        }
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $this->memberList = new MemberList();
        $this->memberList->getActiveWithStatsByGuildID($this->guildID);
    }

    public function sortOrderField($members) {
        usort($members, function($a, $b) {
            if ($this->sortOrder == 'DESC') {
                $firstData = $a;
                $secondData = $b;
            } else {
                $firstData = $b;
                $secondData = $a;
            }

            if ($this->sortField == 'ap') {
                $first = $second = 0;
                if (isset($firstData->stats['this']['artefactPower']) && isset($firstData->stats['last']['artefactPower'])) {
                    $first = $firstData->stats['this']['artefactPower'] - $firstData->stats['last']['artefactPower'];
                }
                if (isset($secondData->stats['this']['artefactPower']) && isset($secondData->stats['last']['artefactPower'])) {
                    $second = $secondData->stats['this']['artefactPower'] - $secondData->stats['last']['artefactPower'];
                }

                $retval = $first <=> $second;
                if ($retval) return $retval;
                return $a->nameNormalize <=> $b->nameNormalize;
            } else if ($this->sortField == 'al') {
                $first = $second = 0;
                if (isset($firstData->stats['this']['artefactweaponLevel'])) {
                    $first = $firstData->stats['this']['artefactweaponLevel'];
                } else if (isset($firstData->stats['last']['artefactweaponLevel'])) {
                    $first = $firstData->stats['last']['artefactweaponLevel'];
                }

                if (isset($secondData->stats['this']['artefactweaponLevel'])) {
                    $second = $secondData->stats['this']['artefactweaponLevel'];
                } else if (isset($secondData->stats['last']['artefactweaponLevel'])) {
                    $second = $secondData->stats['last']['artefactweaponLevel'];
                }

                $retval = $first <=> $second;
                if ($retval) return $retval;
                return $a->nameNormalize <=> $b->nameNormalize;
            } else if ($this->sortField == 'name') {
                $retval = $secondData->nameNormalize <=> $firstData->nameNormalize;
                return $retval;
            } else if ($this->sortField == 'gear') {
                $first = $second = 0;
                if (isset($firstData->stats['this']['iLevel'])) {
                    $first = $firstData->stats['this']['iLevel'];
                } else if (isset($firstData->stats['last']['iLevel'])) {
                    $first = $firstData->stats['last']['iLevel'];
                }

                if (isset($secondData->stats['this']['iLevel'])) {
                    $second = $secondData->stats['this']['iLevel'];
                } else if (isset($secondData->stats['last']['iLevel'])) {
                    $second = $secondData->stats['last']['iLevel'];
                }

                $retval = $first <=> $second;
                if ($retval) return $retval;
                return $a->nameNormalize <=> $b->nameNormalize;
            } else if ($this->sortField == 'role') {
                $first  = (isset($firstData->role->roleID)) ? $firstData->role->roleID : 9999;
                $second = (isset($secondData->role->roleID)) ? $secondData->role->roleID : 9999;
                $retval = $first <=> $second;
                if ($retval) return $retval;
                return $a->nameNormalize <=> $b->nameNormalize;
            }
        });

        return $members;
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'sortField' => $this->sortField,
            'sortOrder' => $this->sortOrder,
            'guild' => $this->guild,
            'memberList' => $this->sortOrderField($this->memberList->getObjects())
        ]);
    }

}