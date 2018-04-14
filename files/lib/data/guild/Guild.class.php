<?php
namespace guild\data\guild;
use guild\system\game\GameHandler;
use guild\system\role\RoleHandler;
use wcf\data\DatabaseObject;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\util\JSON;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 *
 * @property-read	integer		    $guildID	    unique id of the guild
 * @property-read	string		    $name		    name of the guild
 * @property-read	integer     	$gameID	        game id to which the guild belongs
 * @property-read	string		    $apiLocale	    Api locale settings
 * @property-read	string	        $apiRegion		Api region settings
 * @property-read	string          $apiGuild	    Api name of the guild to sync
 * @property-read	string|null     $apiServer	    Api server if needed
 * @property-read	integer     	$apiMinRank	    Api min rank (wow) if needed
 * @property-read	integer		    $isActive	    is 1 if the guild is active
 */
class Guild extends DatabaseObject implements IRouteController {
    /**
     * @inheritDoc
     */
	public $member = [];

    /**
     * @inheritDoc
     */
    public function getApiData($field) {
        $data = JSON::decode($this->apiData);

        if (isset($data[$field])) {
            return $data[$field];
        }

        return '';
    }

    /**
     * Returns the article's title.
     *
     * @return      string
     */
    public function getTitle() {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getSlugTitle() {
        preg_match('/title=(.*)/', LinkHandler::getInstance()->getLink('', ['application' => 'guild', 'title' => $this->getTitle()]), $title);
        return $this->guildID . '-' . (isset($title[1]) ? $title[1] : 'n-a');
    }

    /**
     * @inheritDoc
     */
    public function getLink() {
        return LinkHandler::getInstance()->getLink($this->getSlugTitle(), [
            'application' => 'guild',
            'forceFrontend' => true
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getGame() {
        if ($this->gameID !== null) {
            return GameHandler::getInstance()->getGame($this->gameID);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getRoles() {
        if ($this->gameID !== null) {
            return RoleHandler::getInstance()->getRolesByGame($this->gameID);
        }

        return null;
    }

    /**
     * Returns the guild's image.
     *
     * @return	ViewableMedia|null
     */
    public function getImage() {
        return null;
    }
}