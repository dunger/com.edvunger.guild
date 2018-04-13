<?php
namespace guild\system\game\api\wow;
use guild\data\avatar\AvatarList;
use guild\data\member\MemberAction;
use guild\data\member\MemberList;
use guild\data\wow\encounter\EncounterList;
use guild\data\wow\instance\InstanceList;
use guild\system\guild\GuildHandler;
use wcf\system\WCF;
use wcf\util\DateUtil;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WoW {

    /**
     * @inheritDoc
     */
    private $maxChars = 99;

    /**
     * @inheritDoc
     */
    private $minKills = 5;

    /**
     * @inheritDoc
     */
    private $week = null;

    /**
     * @inheritDoc
     */
    private $buttons = [
        'encounter' => [
            'title' => 'guild.acp.game.wow.encounter',
            'icon' => 'fa-bug',
            'controller' => 'WowEncounterList',
        ],
        'instance' => [
            'title' => 'guild.acp.game.wow.instances',
            'icon' => 'fa-fort-awesome',
            'controller' => 'WowInstanceList',
        ]
    ];

    /**
     * @inheritDoc
     */
    private $guildButtons = [];

    /**
     * @inheritDoc
     */
    private $fields = [
        'locale' => [
            'type' => 'select',
            'valid' => ['en_US', 'es_MX', 'pt_BR', 'en_GB', 'es_ES', 'fr_FR', 'ru_RU', 'de_DE', 'pt_PT', 'it_IT', 'ko_KR', 'zh_TW', 'zh_CN', 'zh_CN']
        ],
        'region' => [
            'type' => 'select',
            'valid' => ['europe', 'korea', 'taiwan', 'china', 'asian']
        ],
        'realm' => [
            'type' => 'select',
            'valid' => ['Alleria', 'Antonidas', 'Teldrassil', 'Aegwynn', 'Aerie Peak', 'Agamaggan', 'Aggra (Portugiesisch)', 'Aggramar', 'Ahn\'Qiraj', 'Al\'Akir', 'Alexstrasza', 'Alonsus', 'Aman\'thul', 'Ambossar', 'Anachronos', 'Anetheron', 'Anub\'arak', 'Arak-arahm', 'Arathi', 'Arathor', 'Archimonde', 'Area 52', 'Argent Dawn', 'Arthas', 'Arygos', 'Ashenvale', 'Aszune', 'Auchindoun', 'Azjol-Nerub', 'Azshara', 'Azuregos', 'Azuremyst', 'Baelgun', 'Balnazzar', 'Blackhand', 'Blackmoore', 'Blackrock', 'Blackscar', 'Blade\'s Edge', 'Bladefist', 'Bloodfeather', 'Bloodhoof', 'Bloodscalp', 'Blutkessel', 'Booty Bay', 'Borean Tundra', 'Boulderfist', 'Bronze Dragonflight', 'Bronzebeard', 'Burning Blade', 'Burning Legion', 'urning Steppes', 'C\'Thun', 'Chamber of Aspects', 'Chants éternels', 'Cho\'gall', 'Chromaggus', 'Colinas Pardas', 'Confrérie du Thorium', 'Conseil des Ombres', 'Crushridge', 'Culte de la Rive noire', 'Daggerspine', 'Dalaran', 'Dalvengyr', 'Darkmoon Faire', 'Darksorrow', 'Darkspear', 'Das Konsortium', 'Das Syndikat', 'Deathguard', 'Deathweaver', 'Deathwing', 'Deephome', 'Defias Brotherhood', 'Dentarg', 'Der Abyssische Rat', 'Der Mithrilorden', 'Der Rat von Dalaran', 'Destromath', 'Dethecus', 'Die Aldor', 'Die Arguswacht', 'Die Nachtwache', 'Die Silberne Hand', 'Die Todeskrallen', 'Die ewige Wacht', 'Doomhammer', 'Draenor', 'Dragonblight', 'Dragonmaw', 'Drak\'thul', 'Drek\'Thar', 'Dun Modr', 'Dun Morogh', 'Dunemaul', 'Durotan', 'Earthen Ring', 'chsenkessel', 'Eitrigg', 'Eldre\'Thalas', 'Elune', 'Emerald Dream', 'Emeriss', 'Eonar', 'Eredar', 'Eversong', 'Executus', 'Exodar', 'Festung der St\u00fcrme', 'Fordragon', 'Forscherliga', 'Frostmane', 'Frostmourne', 'Frostwhisper', 'Frostwolf', 'Galakrond', 'Garona', 'Garrosh', 'Genjuros', 'Ghostlands', 'Gilneas', 'Goldrinn', 'Gordunni', 'Gorgonnash', 'Greymane', 'Grim Batol', 'Grom', 'Gul\'dan', 'Hakkar', 'Haomarush', 'Hellfire', 'Hellscream', 'Howling Fjord', 'Hyjal', 'Illidan', 'Jaedenar', 'Kael\'Thas', 'Karazhan', 'Kargath', 'Kazzak', 'Kel\'Thuzad', 'Khadgar', 'Khaz Modan', 'Khaz\'goroth', 'Kil\'jaeden', 'Kilrogg', 'Kirin Tor', 'Kor\'gall', 'Krag\'jin', 'Krasus', 'Kul Tiras', 'Kult der Verdammten', 'La Croisade \u00e9carlate', 'Laughing Skull', 'Les Clairvoyants', 'Les Sentinelles', 'Lich King', 'Lightbringer', 'Lightning\'s Blade', 'Lordaeron', 'Los Errantes', 'Lothar', 'Madmortem', 'Magtheridon', 'Mal\'Ganis', 'Malfurion', 'Malorne', 'Malygos', 'Mannoroth', 'Mar\u00e9cage de Zangar', 'Mazrigos', 'Medivh', 'Minahonda', 'Moonglade', 'Mug\'thol', 'Nagrand', 'Nathrezim', 'Naxxramas', 'Nazjatar', 'Nefarian', 'Nemesis', 'Neptulon', 'Ner\'zhul', 'Nera\'thor', 'Nethersturm', 'Nordrassil', 'Norgannon', 'Nozdormu', 'Onyxia', 'Outland', 'Perenolde', 'Pozzo dell\'Eternit\u00e0', 'Proudmoore', 'Quel\'Thalas', 'Ragnaros', 'Rajaxx', 'Rashgarroth', 'Ravencrest', 'Ravenholdt', 'Razuvious', 'Rexxar', 'Runetotem', 'Sanguino', 'Sargeras', 'Saurfang', 'Scarshield Legion', 'Sen\'jin', 'Shadowsong', 'Shattered Halls', 'Shattered Hand', 'Shattrath', 'Shen\'dralar', 'Silvermoon', 'Sinstralis', 'Skullcrusher', 'Soulflayer', 'Spinebreaker', 'Sporeggar', 'Steamwheedle Cartel', 'Stormrage', 'Stormreaver', 'Stormscale', 'Sunstrider', 'Suramar', 'Sylvanas', 'Taerar', 'Talnivarr', 'Tarren Mill', 'Temple noir', 'Terenas', 'Terokkar', 'Terrordar', 'The Maelstrom', 'The Sha\'tar', 'The Venture Co', 'Theradras', 'Thermaplugg', 'Thrall', 'Throk\'Feroth', 'Thunderhorn', 'Tichondrius', 'Tirion', 'Todeswache', 'Trollbane', 'Turalyon', 'Twilight\'s Hammer', 'Twisting Nether', 'Tyrande', 'Uldaman', 'Ulduar', 'Uldum', 'Un\'Goro', 'Varimathrs', 'Vashj', 'Vek\'lor', 'Vek\'nilash', 'Vol\'jin', 'Wildhammer', 'Wrathbringer', 'Xavius', 'Ysera', 'Ysondre', 'Zenedar', 'Zirkel des Cenarius', 'Zul\'jin', 'Zuluhed']
        ],
        'guild' => [
            'type' => 'text',
            'valid' => '^.+$'
        ],
        'maxRank' => [
            'type' => 'int',
            'valid' => '^\d+$'
        ],
    ];

    /**
     * @inheritDoc
     */
    public function cronjob($game) {
        /*
         * Calc with -56hours because the wow id reset is on Wednesday
         */
        $this->week = DateUtil::getDateTimeByTimestamp(strtotime("-56 hour"));
        $guilds = GuildHandler::getInstance()->getGuildByGameID($game->gameID);

        if (empty($guilds)) {
            return;
        }

        $encounter = new EncounterList();
        $encounter->sqlJoins = "LEFT JOIN guild".WCF_N."_wow_instance wow_instance ON (wow_instance.instanceID = wow_encounter.instanceID)";
        $encounter->getConditionBuilder()->add('wow_instance.isActive = 1', []);
        $encounter->readObjects();

        $avatar = new AvatarList();
        $avatar->getActive();

        $statistics = [];
        $sql = "SELECT  *
		        FROM    guild".WCF_N."_wow_statistic_type";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();
        while ($row = $statement->fetchArray()) {
            $statistics[$row['type']][ $row['typeID']] = $row['value'];
        }

        foreach ($guilds as $guild) {
            $region = new jpWoWRegion($guild->getApiData('region'), $guild->getApiData('locale'));

            $wow = new jpWoW($region);
            $wow->setApiKey($guild->getGame()->apiKey);

            $members = $wow->getGuildMembers($guild->getApiData('guild'), $guild->getApiData('realm'));

            if (!$members || isset($members['code'])) {
                continue;
            }

            $memberStats = [];
            foreach ($members['members'] as $member) {
                if ($member['rank'] > $guild->getApiData('maxRank') || sizeof($memberStats) > $this->maxChars) {
                    continue;
                }

                $char = $wow->getCharacterFields($member['character']['name'], $member['character']['realm'], ['achievements', 'items', 'stats', 'statistics']);
                if (!$char || isset($char['code']) || (isset($char['status']) && $char['status'] == 'nok')) {
                    continue;
                }

                $lastUpdate = DateUtil::getDateTimeByTimestamp(strtotime("-56 hour", substr((string) $char['lastModified'], 0, -3)));

                preg_match('/^.+\/[0-9]+\/([0-9]+)-avatar\.jpg$/', $char['thumbnail'], $result);
                $memberID = (int)$result[1];

                $memberStats[$memberID]['info'] = [
                    'memberID'	 => $memberID,
                    'name'		 => $char['name'],
                    'thumbnail'	 => $char['thumbnail'],
                    'classID'	 => $char['class'],
                    'lastUpdate' => $lastUpdate
                ];

                /*
                 * Maybe this work to get only this an last week?
                 */
                if ($lastUpdate->format("YW") < ($this->week->format("YW") -1)) {
                    continue;
                }

                /*
                 * read charakter stats
                 */
                if (isset($statistics['charakter'])) {
                    foreach ($statistics['charakter'] as $key => $charakterStats) {
                        if (isset($char[$charakterStats])) {
                            $memberStats[$memberID]['data'][] = [
                                'typeID'	=> $key,
                                'value'		=> $char[$charakterStats]
                            ];
                        }
                    }

                    /*
                     * not possible to get dynamic!
                     */
                    $relicItems = ['mainHand', 'offHand'];
                    $relicCount = $artefactweaponLevel = 0;

                    foreach ($relicItems as $item) {
                        if (isset($char['items'][$item])) {
                            $relicItem = $char['items'][$item];
                            if ($relicItem['quality'] > 4) {
                                $artifactRank = 0;
                                $relicCount += count($relicItem['relics']);
                            }
                        }
                    }

                    if (isset($char['items']['mainHand']) && $char['items']['mainHand']['quality'] == 6) {
                        if (sizeof($char['items']['mainHand']['artifactTraits']) > 0) {
                            foreach ($char['items']['mainHand']['artifactTraits'] as $trait) {
                                $artefactweaponLevel += $trait['rank'];
                            }

                            $artefactweaponLevel -= $relicCount;
                        } else if (isset($char['items']['offHand']) && sizeof($char['items']['offHand']['artifactTraits']) > 0) {
                            foreach ($char['items']['offHand']['artifactTraits'] as $trait) {
                                $artefactweaponLevel += $trait['rank'];
                            }

                            $artefactweaponLevel -= $relicCount;
                        }
                    }

                    $memberStats[$memberID]['data'][] = [
                        'typeID'	=> array_search('artefactweaponLevel', $statistics['charakter']),
                        'value'		=> $artefactweaponLevel
                    ];

                    $iLevel = $itemsCount = 0;
                    foreach ($char['items'] as $slot => $item) {
                        if (!isset($item['itemLevel']) || in_array($slot, ['shirt', 'tabard'])) {
                            continue;
                        }

                        $itemsCount++;
                        $iLevel += $item['itemLevel'];
                    }

                    $memberStats[$memberID]['data'][] = [
                        'typeID'	=> array_search('iLevel', $statistics['charakter']),
                        'value'		=> ($itemsCount > 0) ? round($iLevel/$itemsCount, 2) : $char['items']['averageItemLevelEquipped']
                    ];
                }

                /*
                 * read achievements stats
                 */
                if (isset($statistics['achievements'])) {
                    foreach ($statistics['achievements'] as $key => $achievement) {
                        $achievementKey = array_search($achievement, $char['achievements']['criteria']);

                        $memberStats[$memberID]['data'][] = [
                            'typeID'	=> $key,
                            'value'		=> ($achievementKey && isset($char['achievements']['criteriaQuantity'][$achievementKey])) ? $char['achievements']['criteriaQuantity'][$achievementKey] : 0
                        ];
                    }
                }

                /*
                 * read instance stats
                 */
                if (isset($statistics['instance'])) {
                    foreach ($statistics['instance'] as $key => $achievement) {
                        $quantity = 0;
                        $achievementIDs = explode(';', $achievement);

                        foreach ($achievementIDs as $id) {
                            $search = array_search($id, array_column($char['statistics']['subCategories']['5']['subCategories']['6']['statistics'], 'id'));

                            if ($search !== false && $search !== NULL) {
                                $quantity += $char['statistics']['subCategories']['5']['subCategories']['6']['statistics'][$search]['quantity'];
                            }
                        }

                        $memberStats[$memberID]['data'][] = [
                            'typeID'	=> $key,
                            'value'		=> $quantity
                        ];
                    }
                }

                foreach ($char['statistics']['subCategories']['5']['subCategories']['6']['statistics'] as $boss) {
                    if ($encounter->search($boss['id']) != null && $boss['quantity']) {
                        $encounter->objects[$boss['id']]->killCounter++;
                    }
                }
            }

            $memberList = new MemberList();
            $memberList->getByGuildID($guild->guildID);

            foreach ($memberList as $member) {
                if (!isset($memberStats[$member->memberID]) && $member->maybeActive == true) {
                    $raidMemberAction = new MemberAction([$member->memberID], 'update', ['data' => ['isApiActive' => 0]]);
                    $raidMemberAction->executeAction();
                } else if (isset($memberStats[$member->memberID]) && $member->maybeActive == false) {
                    $raidMemberAction = new MemberAction([$member->memberID], 'update', ['data' => ['isActive' => 1,'isApiActive' => 1]]);
                    $raidMemberAction->executeAction();
                }
            }

            foreach ($memberStats as $member) {
                if ($memberList->search($member['info']['memberID']) == null) {
                    $memberAction = new MemberAction([], 'create', [
                        'data' => [
                            'memberID'		=> $member['info']['memberID'],
                            'guildID'       => $guild->guildID,
                            'name'			=> $member['info']['name'],
                            'thumbnail' 	=> $member['info']['thumbnail'],
                            'avatarID'		=> $avatar->searchByAutoAssignment($member['info']['classID']),
                            'userID'		=> null,
                            'isActive'		=> 1,
                            'isApiActive'   => 1
                        ]
                    ]);
                    $memberAction->executeAction();
                }

                if (isset($member['data'])) {
                    $sql = "REPLACE INTO  guild".WCF_N."_wow_statistic
                                    (memberID, guildID, week, typeID, value)
                                    VALUES (?, ?, ?, ?, ?)";
                    $statement = WCF::getDB()->prepareStatement($sql);

                    WCF::getDB()->beginTransaction();
                    foreach ($member['data'] as $data) {
                        $statement->execute([
                            $member['info']['memberID'],
                            $guild->guildID,
                            $member['info']['lastUpdate']->format("YW"),
                            $data['typeID'],
                            $data['value']
                        ]);
                    }
                    WCF::getDB()->commitTransaction();
                }
            }

            /*
             * Maybe later?
            $memberList = new MemberList();
            $userIDs = $memberList->getActiveUserIDs($guild->guildID, true);

            if (!empty($userIDs)) {
                $avatarList = new UserAvatarList();
                $avatarList->getConditionBuilder()->add('userID IN (?)', [$userIDs]);
                $avatarList->readObjects();
                foreach ($avatarList as $avatar) {
                    if (!preg_match('/^([0-9]+\-avatar\.jpg)$/', $avatar->avatarName)) {
                        $userIDs = array_diff($userIDs, [$avatar->userID]);
                    }
                }

                $userList = new UserList();
                $userList->getConditionBuilder()->add('user_table.userID IN (?)', [$userIDs]);
                $userList->readObjects();

                foreach ($userIDs as $memberID => $userID) {
                    $userEditor = new UserEditor($userList->objects[$userID]);
                    $userAvatarAction = new UserAvatarAction([], 'fetchRemoteAvatar', [
                        'url' => 'https://render-eu.worldofwarcraft.com/character/' . $memberStats[$memberID]['info']['thumbnail'],
                        'userEditor' => $userEditor
                    ]);
                    $userAvatarAction->executeAction();
                }
            }
            */

            /*
             * Boss kills by guild
             */
            $sql = "REPLACE INTO  guild".WCF_N."_wow_encounter_kills
                            (encounterID, guildID, isKilled)
                            VALUES (?, ?, ?)";
            $statement = WCF::getDB()->prepareStatement($sql);
            WCF::getDB()->beginTransaction();
            foreach ($encounter as $boss) {
                $statement->execute([
                    $boss->encounterID,
                    $guild->guildID,
                    (($boss->killCounter > $this->minKills) ? 1 : 0)
                ]);
            }
            WCF::getDB()->commitTransaction();
        }

        return;
    }

    /**
     * @inheritDoc
     */
    public function getButtons() {
        return $this->buttons;
    }

    /**
     * @inheritDoc
     */
    public function getGuildButtons() {
        return $this->guildButtons;
    }

    /**
     * @inheritDoc
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @inheritDoc
     */
    public function getInstanceClass() {
        return new InstanceList();
    }
}