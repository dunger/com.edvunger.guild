<?php
namespace guild\data\member;
use wcf\data\DatabaseObjectEditor;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class MemberEditor extends DatabaseObjectEditor {
    protected static $baseClass = Member::class;

    public function update(array $parameters = []) {
        if (empty($parameters) || !isset($parameters['guildID'])) return;

        $updateSQL = '';
        $statementParameters = [];
        foreach ($parameters as $key => $value) {
            if (!empty($updateSQL)) $updateSQL .= ', ';
            $updateSQL .= $key . ' = ?';
            $statementParameters[] = $value;
        }
        $statementParameters[] = $this->getObjectID();
        $statementParameters[] = $parameters['guildID'];

        $sql = "UPDATE	".static::getDatabaseTableName()."
			SET	".$updateSQL."
			WHERE	".static::getDatabaseTableIndexName()." = ?
			AND guildID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute($statementParameters);
    }
}