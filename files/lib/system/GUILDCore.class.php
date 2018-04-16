<?php
namespace guild\system;
use guild\page\GuildPage;
use guild\system\request\route\RequestRoute;
use wcf\system\application\AbstractApplication;
use wcf\system\request\RouteHandler;

/**
 * Application core.
 *
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GUILDCore extends AbstractApplication {
	/**
	 * @see	\wcf\system\application\AbstractApplication::$abbreviation
	 */
	protected $abbreviation = 'guild';

	/**
	 * @inheritDoc
	 */
	protected $primaryController = GuildPage::class;
	
	/**
	 * @see \wcf\system\application\AbstractApplication::__run()
	 */
	public function __run() {
		if (!$this->isActiveApplication()) {
			return;
		}

        $route = new RequestRoute();
        RouteHandler::getInstance()->addRoute($route);
	}
}

