<?php
namespace guild\system\condition;
use guild\data\guild\Guild;
use guild\data\guild\GuildList;
use guild\system\guild\GuildHandler;
use wcf\data\DatabaseObject;
use wcf\data\DatabaseObjectList;
use wcf\system\condition\AbstractMultiCategoryCondition;
use wcf\system\condition\AbstractSelectCondition;
use wcf\system\condition\IObjectCondition;
use wcf\system\condition\IObjectListCondition;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class WowRaidProgressCondition extends AbstractSelectCondition implements IObjectListCondition {
    /**
     * @inheritDoc
     */
    protected $fieldName = 'guildIDs';

    /**
     * @inheritDoc
     */
    protected $label = 'guild.acp.guild.name';

    /**
     * @inheritDoc
     */
    public function addObjectListCondition(DatabaseObjectList $objectList, array $conditionData) {
        if (!($objectList instanceof GuildList)) {
            throw new \InvalidArgumentException("Object list is no instance of '".GuildList::class."', instance of '".get_class($objectList)."' given.");
        }

        $objectList->getConditionBuilder()->add('guild.guildID IN (?)', [$conditionData[$this->fieldName]]);
    }

    /**
     * @inheritDoc
     */
    public function checkObject(DatabaseObject $object, array $conditionData) {
        if (!($object instanceof Guild) && (!($object instanceof DatabaseObjectDecoraton) || !($object->getDecoratedObject() instanceof Guild))) {
            throw new \InvalidArgumentException("Object is no (decorated) instance of '".Guild::class."', instance of '".get_class($object)."' given.");
        }

        return in_array($object->guildID, $conditionData[$this->fieldName]);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldElement() {
        $guilds = GuildHandler::getInstance()->getGuildByGameTag('wow');
        $guildCount = count($guilds);

        $fieldElement = '<select name="'.$this->fieldName.'[]" id="'.$this->fieldName.'" multiple size="'.($guildCount > 10 ? 10 : $guildCount).'">';
        foreach ($guilds as $guild) {
            $fieldElement .= '<option value="'.$guild->guildID.'"'.(in_array($guild->guildID, $this->fieldValue) ? ' selected' : '').'>'.$guild->getTitle().'</option>';
        }
        $fieldElement .= "</select>";

        return $fieldElement;
    }

    /**
     * @inheritDoc
     */
    protected function getOptions() {
        return GuildHandler::getInstance()->getGuildByGameTag('wow');
    }
}