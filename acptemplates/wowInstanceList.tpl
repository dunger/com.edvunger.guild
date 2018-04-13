{include file='header' pageTitle='guild.acp.game.wow.instance.settings'}

{if $objects|count}
    <script data-relocate="true">
        $(function() {
            new WCF.Action.Toggle('guild\\data\\wow\\instance\\InstanceAction', '.guildWowInstanceRow');
            new WCF.Action.Delete('guild\\data\\wow\\instance\\InstanceAction', '.guildWowInstanceRow');
        });
    </script>
{/if}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.wow.instance.settings{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='WowInstanceAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}guild.acp.game.wow.instance.add{/lang}</span></a></li>
            <li><a href="{link application='guild' controller='GameList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}guild.acp.games{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>

{hascontent}
    <div class="paginationTop">
        {content}{pages print=true assign=pagesLinks application="guild" controller="WowInstanceList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox">
        <table class="table">
            <thead>
            <tr>
                <th class="columnID columnInstanceID{if $sortField == 'instanceID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link application='guild' controller='WowInstanceList'}pageNo={@$pageNo}&sortField=instanceID&sortOrder={if $sortField == 'instanceID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance.instanceID{/lang}</a></th>
                <th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='WowInstanceList'}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance.name{/lang}</a></th>
                <th class="columnText columnTitle{if $sortField == 'title'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='WowInstanceList'}pageNo={@$pageNo}&sortField=title&sortOrder={if $sortField == 'title' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance.title{/lang}</a></th>
                <th class="columnText columnMapID{if $sortField == 'mapID'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='WowInstanceList'}pageNo={@$pageNo}&sortField=mapID&sortOrder={if $sortField == 'mapID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance.map{/lang}</a></th>
                <th class="columnText columnDifficulty{if $sortField == 'difficulty'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='WowInstanceList'}pageNo={@$pageNo}&sortField=difficulty&sortOrder={if $sortField == 'difficulty' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance.difficulty{/lang}</a></th>
                <th class="columnText columnIsRaid{if $sortField == 'isRaid'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='WowInstanceList'}pageNo={@$pageNo}&sortField=isRaid&sortOrder={if $sortField == 'isRaid' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance.isRaid{/lang}</a></th>

                {event name='columnHeads'}
            </tr>
            </thead>

            <tbody>
            {foreach from=$objects item=instance}
                <tr class="jsGuildWowInstanceListRow guildWowInstanceRow" data-member-id="{@$instance->instanceID}">
                    <td class="columnIcon">
                        {if $__wcf->session->getPermission('admin.guild.canManageGames')}
                            <span class="icon icon16 fa-{if $instance->isActive}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if $instance->isActive}disable{else}enable{/if}{/lang}" data-object-id="{@$instance->instanceID}"></span>
                            <a href="{link application="guild" controller='WowInstanceEdit' id=$instance->instanceID}{/link}"><span title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip icon icon16 fa-pencil"></span></a>
                            <span title="{lang}wcf.global.button.delete{/lang}" class="jsDeleteButton jsTooltip icon icon16 fa-times" data-object-id="{@$instance->instanceID}" data-confirm-message-html="{lang __encode=true}guild.acp.game.wow.instance.delete.sure{/lang}"></span>
                        {/if}

                        {event name='rowButtons'}
                    </td>
                    <td class="columnID columnInstanceID">{@$instance->instanceID}</td>
                    <td class="columnTitle columnName">{$instance->name}</td>
                    <td class="columnText columnTitle">{$instance->title}</td>
                    <td class="columnText columnMapID">{$instance->mapID}</td>
                    <td class="columnText columnDifficulty">{$instance->difficulty}</td>
                    <td class="columnText columnIsRaid">{@$instance->isRaid}</td>

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