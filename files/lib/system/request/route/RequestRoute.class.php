<?php
namespace guild\system\request\route;
use guild\data\member\Member;
use guild\system\guild\GuildHandler;
use wcf\action\CoreRewriteTestAction;
use wcf\system\exception\IllegalLinkException;
use wcf\system\request\route\DynamicRequestRoute;
use wcf\system\request\route\IRequestRoute;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class RequestRoute extends DynamicRequestRoute implements IRequestRoute {

    /**
     * Sets default routing information.
     */
    protected function init() {
        $this->setPattern('~
			/?
			(?:
				(?P<guildID>\d+)
					(?:
						-
						(?P<guildTitle>[^/]+)
					)?
				(?:
					/
					(?P<memberID>\d+)
					(?:
						-
						(?P<memberTitle>[^/]+)
					)?
				)?
			)?
		~x');
        $this->setBuildSchema('/{controller}/{id}-{title}/');
    }

    /**
     * @inheritDoc
     */
    public function matches($requestURL) {
        preg_match($this->pattern, $requestURL, $matches);
        if (!isset($matches['guildID'])) {
            /*
             * add rewrite test
             */
            if (strpos($requestURL,  'core-rewrite-test') !== false) {
                $test = new CoreRewriteTestAction();
                $test->readParameters();
                $test->execute();
            }

            parent::init();
            return parent::matches($requestURL);
        } else {
            $guild = GuildHandler::getInstance()->getGuild($matches['guildID']);
            if ($guild === null) {
                throw new IllegalLinkException();
            }

            if (isset($matches['memberID'])) {
                $member = new Member($matches['memberID']);
                if ($member !== null) {
                    $matches['controller'] = $guild->getGame()->detailsMemberPage;
                }

            } else {
                if (empty($guild->getGame()->detailsPage)) {
                    throw new IllegalLinkException();
                }
                $matches['controller'] = $guild->getGame()->detailsPage;
            }

            foreach ($matches as $key => $value) {
                if (!is_numeric($key)) {
                    $this->routeData[$key] = $value;
                }
            }

            $this->routeData['isDefaultController'] = (!isset($this->routeData['controller']));

            return true;
        }
    }

    /**
     * @inheritDoc
     */
    public function setIsACP($isACP) {
        parent::setIsACP(false);
    }

    /**
     * @inheritDoc
     */
    public function canHandle(array $components) {
        if (isset($components['application']) && $components['application'] == 'guild') {
            return parent::canHandle($components);
        }
        return false;
    }
}