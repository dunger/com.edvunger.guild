<?php
namespace guild\system\worker;
use wcf\data\application\Application;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\system\worker\AbstractWorker;

/**
 * @author		David Unger <david@edv-unger.com>
 * @copyright	2018 David Unger
 * @license		GPL <http://www.gnu.org/licenses/gpl-3.0>
 * @package		com.edvunger.guild
 */
class TemplateWorker extends AbstractWorker {
    /**
     * @inheritDoc
     */
    protected $limit = 1;

    /**
     * @inheritDoc
     */
    public function countObjects() {
        $this->count = 1;
    }

    /**
     * @inheritDoc
     */
    public function getProceedURL() {
        return LinkHandler::getInstance()->getLink('RebuildData');
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        WCF::getSession()->checkPermissions(['admin.management.canRebuildData']);
    }

    /**
     * @inheritDoc
     */
    public function execute() {
        $packageDir = Application::getDirectory('calendar');

        /*
         * get JS Data from calendar/js/Calendar.js
         * get amd Chane: Calendar.Event.Date.Participation.Manager
         */
        $fileContent = file_get_contents($packageDir.'js/Calendar.min.js');
        $search = '/Calendar.Event.Date.Participation.Manager=Class.extend\({(.*)}\),Calendar.Event.Date.Participation.RemoveParticipant=WCF.Action.Delete.extend\({/s';
        preg_match($search, $fileContent, $jsFunction);
        $jsFunction = $jsFunction[1];
        $search = [
            "_decision:\"\",",
            "this._decision=\"\",",
            "$(\".jsCalendarButtonParticipation\").click($.proxy(this._click,this))}",
            "message:$(\"#eventDateParticipationMessageContainer\"),",
            "message:$(\"#eventDateParticipationMessage\")",
            "$.trim(this._ui.element.message.val())"
        ];
        $replace = [
            "_decision:\"\",_guildMember:0,_guildRole:0,",
            "this._decision=\"\",this._guildMember=0,this._guildRole=0,",
            "$(\".jsCalendarButtonGuildParticipation\").click($.proxy(this._click,this))}",
            "message:$(\"#eventDateParticipationMessageContainer\"),guildMember:$(\"#eventDateParticipationMemberContainer\"),guildRole:$(\"#eventDateParticipationRoleContainer\"),",
            "message:$(\"#eventDateParticipationMessage\"),guildMember:$(\"#eventDateParticipationMember\"),guildRole:$(\"#eventDateParticipationRole\")",
            "$.trim(this._ui.element.message.val()),guildMember:this._ui.element.guildMember.val(),guildRole:this._ui.element.guildRole.val()"
        ];
        $newFunction = str_replace($search, $replace, $jsFunction);

        file_put_contents($packageDir.'js/Calendar.Guild.js', str_replace("Calendar.Event.Date.Guild.Participation.Manager = Class.extend({});", "Calendar.Event.Date.Guild.Participation.Manager = Class.extend({" . $newFunction . "});", file_get_contents($packageDir.'js/Calendar.Guild.js.org')));
        file_put_contents($packageDir.'js/Calendar.Guild.min.js', str_replace("Calendar.Event.Date.Guild.Participation.Manager=Class.extend({});", "Calendar.Event.Date.Guild.Participation.Manager = Class.extend({" . $newFunction . "});", file_get_contents($packageDir.'js/Calendar.Guild.min.js.org')));

        /*
         * Copy calendar/templates/event.tpl
         *
         * Change: include
         * Change: Button listener
         * Change: new participation listener
         */
        $search = [
            "jsCalendarButtonParticipation",
            "{include file='eventDateParticipationList' application='calendar'}",
            'new Calendar.Event.Date.Participation.Manager({@$eventDate->eventDateID});'
        ];
        $replace = [
            "jsCalendarButtonGuildParticipation",
            "{include file='guildEventDateParticipationList' application='calendar'}",
            'new Calendar.Event.Date.Guild.Participation.Manager({@$eventDate->eventDateID});'
        ];
        file_put_contents($packageDir.'templates/guildEvent.tpl', str_replace($search, $replace, file_get_contents($packageDir.'templates/event.tpl')));

        /*
         * Copy calendar/templates/upcomingEventList.tpl
         *
         * Change: add new Box
         * Change: include
         */
        $fileContent = file_get_contents($packageDir.'templates/upcomingEventList.tpl');
        $search = [
            "/({capture assign='sidebarRight'})(.*)({\/capture}.*{capture assign='headerNavigation'})/s",
            "/{include file='groupedEventDateList' application='calendar'}/"
        ];
        $replace = [
            '$1$2
            {hascontent}
                <section class="box">
                    <h2 class="boxTitle">{lang}guild.user.character.select{/lang}</h2>
            
                    <div class="boxContent">
                        <dl>
                            <dt></dt>
                            <dd>
                                <select id="eventDateParticipationMember">
                                    {content}
                                        {foreach from=$memberList item=member}
                                            <option value="{@$member->memberID}" {if $member->isMain} selected{/if} data-default-role="{@$member->roleID}">{lang}{@$member->name}{/lang}</option>
                                        {/foreach}
                                    {/content}
                                </select>
                            </dd>
            
                            <dt></dt>
                            <dd>
                                <select id="eventDateParticipationRole">
                                    {foreach from=$roleList item=role}
                                        <option value="{@$role->roleID}" {if $role->roleID == $mainRoleID} selected{/if}>{lang}{@$role->name}{/lang}</option>
                                    {/foreach}
                                </select>
                            </dd>
                        </dl>
                    </div>
                </section>
            {/hascontent}
            $3',
            "{include file='guildGroupedEventDateList' application='calendar'}"
        ];
        file_put_contents($packageDir.'templates/guildUpcomingEventList.tpl', preg_replace($search, $replace, $fileContent));

        /*
         * Copy calendar/templates/groupedEventDateList.tpl
         *
         * Change: include
         */
        file_put_contents($packageDir.'templates/guildGroupedEventDateList.tpl', str_replace("{include file='eventDateListItem' application='calendar'}","{include file='guildEventDateListItem' application='calendar'}", file_get_contents($packageDir.'templates/groupedEventDateList.tpl')));

        /*
         * Copy calendar/templates/eventDateListItem.tpl
         *
         * Change: Add quick participation
         */
        $search = '<a href="{link controller=\'User\' object=$eventDate->getEvent()->getUserProfile()}{/link}" title="{$eventDate->getEvent()->getUserProfile()->username}">{@$eventDate->getEvent()->getUserProfile()->getAvatar()->getImageTag(48)}</a>';
        $replace = '<ol class="jsOnly">
			<li id="event-decision-yes-{@$eventDate->eventDateID}"{if $eventDate->decision == \'yes\' && $eventDate->decision != \'\'} class="invisible"{/if}><a href="#" class="quickParticipation" data-event-decision="yes" data-event-date-id="{@$eventDate->eventDateID}" data-tooltip="{lang}guild.user.event.decision.yes{/lang}"><span class="icon icon16 fa-check green"></span> <span class="invisible">{lang}guild.user.event.decision.yes{/lang}</span></a></li>
			<li id="event-decision-maybe-{@$eventDate->eventDateID}"{if $eventDate->decision == \'maybe\' && $eventDate->decision != \'\'} class="invisible"{/if}><a href="#" class="quickParticipation" data-event-decision="maybe" data-event-date-id="{@$eventDate->eventDateID}" data-tooltip="{lang}guild.user.event.decision.maybe{/lang}"><span class="icon icon16 fa-question yellow"></span> <span class="invisible">{lang}guild.user.event.decision.maybe{/lang}</span></a></li>
			<li id="event-decision-no-{@$eventDate->eventDateID}"{if $eventDate->decision == \'no\' && $eventDate->decision != \'\'} class="invisible"{/if}><a href="#" class="quickParticipation" data-event-decision="no" data-event-date-id="{@$eventDate->eventDateID}" data-tooltip="{lang}guild.user.event.decision.no{/lang}"><span class="icon icon16 fa-times red"></span> <span class="invisible">{lang}guild.user.event.decision.no{/lang}</span></a></li>
		</ol>';
        file_put_contents($packageDir.'templates/guildEventDateListItem.tpl', str_replace($search, $replace, file_get_contents($packageDir.'templates/eventDateListItem.tpl')));

        /*
         * only needed for WSC 3.0
         */
        if (version_compare(PACKAGE_VERSION, '3.1', '<')) {
            /*
             * Copy calendar/templates/guildEventDateParticipationForm.tpl
             *
             * Change: include
             */
            $fileContent = file_get_contents($packageDir.'templates/eventDateParticipationForm.tpl');
            $search = [
                "/(<dl id=\"eventDateParticipationCompanionsContainer\">)(.*)(<dl id=\"eventDateParticipationMessageContainer\">)/s"
            ];
            $replace = [
                '$1$2
                <dl id="eventDateParticipationMemberContainer">
                    <dt><label for="eventDateParticipationMember">{lang}guild.user.character.select{/lang}</label></dt>
                    <dd><select id="eventDateParticipationMember">
                        {foreach from=$memberList item=member}
                            <option value="{@$member->memberID}" {if $member->roleID == $mainMemberID} selected{/if} data-default-role="{@$member->roleID}">{lang}{@$member->name}{/lang}</option>
                        {/foreach}
                    </select></dd>
                </dl>
            
                <dl id="eventDateParticipationRoleContainer">
                    <dt><label for="eventDateParticipationRole">{lang}guild.user.role.decision{/lang}</label></dt>
                    <dd><select id="eventDateParticipationRole">
                        {foreach from=$roleList item=role}
                            <option value="{@$role->roleID}" {if $role->roleID == $mainRoleID} selected{/if}>{lang}{@$role->name}{/lang}</option>
                        {/foreach}
                    </select></dd>
                </dl>
                $3'
            ];
            file_put_contents($packageDir.'templates/guildEventDateParticipationForm.tpl', preg_replace($search, $replace, $fileContent));

            /*
             * Copy calendar/templates/eventDateParticipationForm.tpl
             *
             * Change: include
             */
            file_put_contents($packageDir.'templates/guildEventDateParticipationFormOwner.tpl', str_replace("{include file='eventDateParticipationForm' application='calendar'}","{include file='guildEventDateParticipationForm' application='calendar'}", file_get_contents($packageDir.'templates/eventDateParticipationFormOwner.tpl')));
        }
    }
}
