<?php
namespace guild\page;
use guild\data\member\Member;
use guild\data\wow\statistic\StatisticList;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;
use wcf\util\DateUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowmemberPage extends AbstractPage {
    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.guild.canViewMemberDetails'];

    /**
     * member id
     * @var	integer
     */
    public $memberID = 0;

    /**
     * guild id
     * @var	integer
     */
    public $guildID = 0;

    /**
     * user object
     * @var	UserProfile
     */
    public $member;

    private $achievementsStatistics = null;
    private $raidStatisics = [];
    private $instanceStatistics = [];
    private $loot = [];
    private $lootFilter = 0;

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['memberID'])) $this->memberID = intval($_REQUEST['memberID']);
        if (isset($_REQUEST['guildID'])) $this->guildID = intval($_REQUEST['guildID']);
        $this->member = Member::getMember($this->memberID, $this->guildID);

        if (!$this->member->memberID) {
            throw new IllegalLinkException();
        }

        if (isset($_REQUEST['lootFilter'])) $this->lootFilter = $_REQUEST['lootFilter'];
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $week = DateUtil::getDateTimeByTimestamp(strtotime("-56 hour"));

        $stats = new StatisticList();
        $this->member->stats = $stats->getStats([$this->member->memberID]);

        $tmp = [];
        $sql = "SELECT		statistic.week, instance.instanceID as instance, instance.title as instanceTitle, statistic_type.description, statistic.value as bossKills, encounter.name as encounterName
				FROM		guild".WCF_N."_wow_statistic_type statistic_type
				LEFT JOIN	guild".WCF_N."_wow_statistic statistic ON (statistic.typeID = statistic_type.typeID)
				LEFT JOIN	guild".WCF_N."_wow_instance instance ON (instance.instanceID = statistic_type.value2)
				LEFT JOIN	guild".WCF_N."_wow_encounter encounter ON (encounter.encounterID = statistic_type.value)
				WHERE		statistic_type.type = 'instance'
					AND 	statistic.memberID = ?
					AND 	statistic.guildID = ?
					AND 	instance.isRaid = 1
					AND 	instance.isActive = 1
					AND 	(statistic.week = ? OR statistic.week = ?)";

        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$this->memberID, $this->guildID, $week->format("YW"), $week->format("YW")-1]);
        while ($row = $statement->fetchArray()) {
            $instance = (object) $row;

            if ($instance->week == $week->format("YW")) {
                if (!isset($this->raidStatisics[$instance->instance])) {
                    $this->raidStatisics[$instance->instance]['instanceTitle'] = $instance->instanceTitle;
                }
                $this->raidStatisics[$instance->instance]['data'][] = $instance;
            } else {
                if (!isset($tmp[$instance->instance])) {
                    $tmp[$instance->instance]['instanceTitle'] = $instance->instanceTitle;
                }
                $tmp[$instance->instance]['data'][] = $instance;
            }
        }
        if (empty($this->raidStatisics) && !empty($tmp)) {
            $this->raidStatisics = $tmp;
        }

        $tmp = [];
        $sql = "SELECT		statistic.week as week, instance.name as instanceTitle, statistic.value as kills
				FROM		guild".WCF_N."_wow_statistic_type statistic_type
				LEFT JOIN	guild".WCF_N."_wow_statistic statistic ON (statistic.typeID = statistic_type.typeID)
				LEFT JOIN	guild".WCF_N."_wow_instance instance ON (instance.instanceID = statistic_type.value2)
				WHERE		statistic_type.type = 'instance'
					AND 	statistic.memberID = ?
					AND 	statistic.guildID = ?
					AND 	instance.isRaid = 0
					AND 	instance.isActive = 1
					AND 	(statistic.week = ? OR statistic.week = ?)";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$this->memberID, $this->guildID, $week->format("YW"), $week->format("YW")-1]);
        while ($row = $statement->fetchArray()) {
            $instance = (object) $row;
            if ($instance->week == $week->format("YW")) {
                $this->instanceStatistics[] = $instance;
            } else {
                $tmp[] = $instance;
            }
        }
        if (empty($this->instanceStatistics) && !empty($tmp)) {
            $this->instanceStatistics = $tmp;
        }

        $tmp = [];
        $achievements = [33096, 33097, 33098, 32028];
        $sql = "SELECT		statistic_type.value, statistic.week as week, statistic.value as runs
				FROM		guild".WCF_N."_wow_statistic_type statistic_type
				LEFT JOIN	guild".WCF_N."_wow_statistic statistic ON (statistic.typeID = statistic_type.typeID)
				WHERE		statistic_type.type = 'achievements'
					AND 	statistic.memberID = ?
					AND 	statistic.guildID = ?
					AND 	(statistic.week = ? OR statistic.week = ?)
					AND 	statistic_type.value IN (?".str_repeat(',?', count($achievements) - 1).")";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array_merge([$this->memberID, $this->guildID, $week->format("YW"), $week->format("YW")-1], [33096, 33097, 33098, 32028]));
        while ($row = $statement->fetchArray()) {
            $achievement = (object) $row;

            if ($achievement->week == $week->format("YW")) {
                $this->achievementsStatistics[$achievement->value] = $achievement->runs;
            } else {
                $tmp[$achievement->value] = $achievement->runs;
            }
        }
        if (empty($this->achievementsStatistics) && !empty($tmp)) {
            $this->achievementsStatistics = $tmp;
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'member'					=> $this->member,
            'raidStatisics'				=> $this->raidStatisics,
            'instanceStatistics'		=> $this->instanceStatistics,
            'achievementsStatistics'	=> $this->achievementsStatistics,
            'background'				=> str_replace('avatar', 'main', $this->member->thumbnail)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function show() {
        parent::show();
    }
}