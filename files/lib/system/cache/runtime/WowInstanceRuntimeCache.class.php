<?php
namespace guild\system\cache\runtime;
use guild\data\wow\instance\InstanceList;
use wcf\system\cache\runtime\AbstractRuntimeCache;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowInstanceRuntimeCache extends AbstractRuntimeCache {
    /**
     * @inheritDoc
     */
    protected $listClassName = InstanceList::class;
}
