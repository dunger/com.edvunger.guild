<?php
namespace guild\system\page\handler;
use guild\system\guild\GuildHandler;
use wcf\system\page\handler\AbstractLookupPageHandler;
use wcf\system\page\handler\IOnlineLocationPageHandler;
use wcf\system\page\handler\TOnlineLocationPageHandler;

/**
 * Menu page handler for the details page.
 *
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class DetailsPageHandler extends AbstractLookupPageHandler implements IOnlineLocationPageHandler {
    use TOnlineLocationPageHandler;

    /**
     * @inheritDoc
     */
    public function getLink($objectID) {
        return GuildHandler::getInstance()->getGuild($objectID)->getLink();
    }

    /**
     * @inheritDoc
     */
    public function isValid($objectID) {
        return GuildHandler::getInstance()->getGuild($objectID) !== null;
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @inheritDoc
     */
    public function isVisible($objectID = null) {
        return GuildHandler::getInstance()->getGuild($objectID) !== null;
    }

    /**
     * @inheritDoc
     */
    public function lookup($searchString) {
        $guilds = GuildHandler::getInstance()->getGuilds();
        $guildsSearch = preg_grep("/^$searchString.*$/", array_column($guilds, 'name', 'guildID'));


        $results = [];
        foreach ($guilds as $guild) {
            if (isset($guildsSearch[$guild->guildID])) {
                $results[] = [
                    'description' => $guild->apiGuild . '[' . $guild->apiServer . ']',
                    'image' => $guild->getImage() ? $guild->getImage()->getElementTag(48) : '',
                    'link' => $guild->getLink(),
                    'objectID' => $guild->guildID,
                    'title' => $guild->getTitle()
                ];
            }
        }

        return $results;
    }
}
