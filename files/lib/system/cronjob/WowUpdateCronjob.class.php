<?php
namespace guild\system\cronjob;
use guild\system\game\api\wow\jpWoW;
use guild\system\game\api\wow\jpWoWRegion;
use guild\system\game\GameHandler;
use wcf\data\cronjob\Cronjob;
use wcf\system\cronjob\AbstractCronjob;
use wcf\util\JSON;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowUpdateCronjob extends AbstractCronjob {
    /**
     * @inheritDoc
     */
    public function execute(Cronjob $cronjob) {
        $game = GameHandler::getInstance()->getGameByTag('wow');

        $region = new jpWoWRegion('europe', 'de_DE');
        $api = new jpWoW($region);
        $api->setApiKey($game->apiKey);

        $zones = $api->getZoneList();
        if (!$zones || isset($zones['code'])) {
            return;
        }

        $apiData = [];
        foreach ($zones['zones'] as $zone) {
            if (!isset($zone['patch']) || version_compare($zone['patch'], '7.0') < 0) {
                continue;
            }

            $bosses = [];
            foreach ($zone['bosses'] as $boss) {
                $bosses[] = [
                    'encounterID' => $boss['id'],
                    'name' => $boss['name']
                ];
            }

            $apiData[] = [
                'instanceID' => $zone['id'],
                'name' => $zone['name'],
                'isRaid' => $zone['isRaid'] ? true : false,
                'availableModes' => $zone['availableModes'],
                'bosses' => $bosses,
                'json' => JSON::encode($zone)
            ];
        }

        wcfDebug($apiData);
    }
}