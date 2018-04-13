{include file='header' pageTitle='guild.acp.game.instance.kills.settings'}

{if $objects|count}
    <script data-relocate="true">
        $(function() {
            new WCF.Action.Toggle('guild\\data\\instance\\kills\\KillsAction', '.guildInstanceKillsRow');
            new WCF.Action.Delete('guild\\data\\instance\\kills\\KillsAction', '.guildInstanceKillsRow');
        });
    </script>
{/if}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.instance.kills.settings{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='InstanceKillsAdd' id=$guildID}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}guild.acp.game.instance.kills.add{/lang}</span></a></li>
            <li><a href="{link application='guild' controller='GuildList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}guild.acp.guild.list{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>

{hascontent}
    <div class="paginationTop">
        {content}{pages print=true assign=pagesLinks application="guild" controller="InstanceList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox">
        <table class="table">
            <thead>
            <tr>
                <th class="columnID columnInstanceKillsID" colspan="2">{lang}guild.acp.game.instance.kills.id{/lang}</th>
                <th class="columnTitle columnName">{lang}guild.acp.game.instance.name{/lang}</th>
                <th class="columnText columnEncounter">{lang}guild.acp.game.instance.encounters{/lang}</th>
                <th class="columnText columnKills">{lang}guild.acp.game.instance.kills{/lang}</th>

                {event name='columnHeads'}
            </tr>
            </thead>

            <tbody>
            {foreach from=$objects item=instanceKills}
                <tr class="jsGuildInstanceListRow guildInstanceKillsRow" data-member-id="{@$instanceKills->killsID}">
                    <td class="columnIcon">
                        {if $__wcf->session->getPermission('admin.guild.canManageGames')}
                            <a href="{link application="guild" controller='InstanceKillsEdit' id=$instanceKills->killsID}{/link}"><span title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip icon icon16 fa-pencil"></span></a>
                            <span title="{lang}wcf.global.button.delete{/lang}" class="jsDeleteButton jsTooltip icon icon16 fa-times" data-object-id="{@$instanceKills->killsID}" data-confirm-message-html="{lang __encode=true}guild.acp.game.wow.instance.kills.delete.sure{/lang}"></span>
                        {/if}

                        {event name='rowButtons'}
                    </td>
                    <td class="columnID columnInstanceID">{@$instanceKills->killID}</td>
                    <td class="columnTitle columnName">{$instanceKills->getInstance()->name}</td>
                    <td class="columnText columnEncounters">{$instanceKills->getInstance()->encounters}</td>
                    <td class="columnText columnEncounters">{$instanceKills->kills}</td>

                    {event name='columns'}
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>

    <footer class="contentFooter">
        {hascontent}
            <div class="paginationBottom">
                {content}{@$pagesLinks}{/content}
            </div>
        {/hascontent}

        <nav class="contentFooterNavigation">
            <ul>
                {event name='contentFooterNavigation'}
            </ul>
        </nav>
    </footer>
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}