<?php
namespace guild\data\game;
use guild\system\game\GameHandler;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\WCF;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class GameAction extends AbstractDatabaseObjectAction {
	/**
	 * @inheritDoc
	 */
    public $className = GameEditor::class;

    /**
     * @inheritDoc
     */
    protected $permissionsUpdate = ['admin.guild.canManageGames'];

    /**
     * Validates permissions and parameters.
     */
    public function validateAction() {
        WCF::getSession()->checkPermissions($this->permissionsUpdate);
    }

    /**
     * @inheritDoc
     */
    public function getFields($fieldsData = [], $fieldsError = []) {
        $gameID = (int)$this->parameters['gameID'];
        $game = GameHandler::getInstance()->getGame($gameID);
        if ($game == null || empty($game->apiClass)) {
            return '';
        }

        $gameClass = new $game->apiClass;
        $fields = $gameClass->getFields();

        $template = '';
        foreach ($fields as $name => $data) {
            switch ($data['type']) {
                case 'text':
                    $template .= '
                        <dl ' . (in_array($name, $fieldsError) ? 'class="formError"' : '') . '>
                            <dt><label for="' . $name . '">' . WCF::getLanguage()->get('guild.acp.game.' . $name) . '</label></dt>
                            <dd>
                                <input type="text" id="' . $name . '" name="' . $name . '" value="' . (isset($fieldsData[$name]) ? $fieldsData[$name] : '') . '" class="long">
                            </dd>
                        </dl>
                    ';
                    break;

                case 'int':
                    $template .= '
                        <dl ' . (in_array($name, $fieldsError) ? 'class="formError"' : '') . '>
                            <dt><label for="' . $name . '">' . WCF::getLanguage()->get('guild.acp.game.' . $name) . '</label></dt>
                            <dd>
                                <input type="text" id="' . $name . '" name="' . $name . '" value="' . (isset($fieldsData[$name]) ? $fieldsData[$name] : '') . '" class="long" min="1">
                            </dd>
                        </dl>
                    ';
                    break;

                case 'select':
                    $option = '';
                    foreach ($data['valid'] as $value) {
                        $option .= '<option value="' . $value . '"' . ((isset($fieldsData[$name]) && $fieldsData[$name] == $value) ? ' selected' : '') . '>' . $value . '</option>';
                    }
                    $template .= '
                        <dl ' . (in_array($name, $fieldsError) ? 'class="formError"' : '') . '>
                            <dt><label for="' . $name . '">' . WCF::getLanguage()->get('guild.acp.game.' . $name) . '</label></dt>
                            <dd>
                                <select id="' . $name . '" name="' . $name . '">
                                    <option value="0">' . WCF::getLanguage()->get('wcf.global.noSelection') . '</option>
                                    ' . $option . '
                                </select>
                            </dd>
                        </dl>
                    ';
                    break;
            }
        }

        return $template;
    }
}