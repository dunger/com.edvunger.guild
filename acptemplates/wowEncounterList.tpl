{include file='header' pageTitle='guild.acp.game.wow.encounter.settings'}

{if $objects|count}
    <script data-relocate="true">
        require(['WoltLabSuite/Core/Ui/Sortable/List'], function (UiSortableList) {
            new UiSortableList({
                containerId: 'instanceList',
                className: 'guild\\data\\wow\\encounter\\EncounterAction',
                offset: {@$startIndex}
            });
        });

        $(function() {
            new WCF.Action.Delete('guild\\data\\wow\\encounter\\EncounterAction', $('.encounterRow'));
        });
    </script>
{/if}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.wow.encounter.settings{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='WowEncounterAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}guild.acp.game.wow.encounter.add{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>

{hascontent}
    <div class="paginationTop">
        {content}{pages print=true assign=pagesLinks application="guild" controller="WowEncounterList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox">
        <table class="table">
            <thead>
            <tr>
                <th class="columnID columnEncounterID{if $sortField == 'instanceID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link application='guild' controller='WowEncounterList'}pageNo={@$pageNo}&sortField=instanceID&sortOrder={if $sortField == 'instanceID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance.instanceID{/lang}</a></th>
                <th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='WowEncounterList'}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance.name{/lang}</a></th>
                <th class="columnText columnInstance{if $sortField == 'title'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='WowEncounterList'}pageNo={@$pageNo}&sortField=title&sortOrder={if $sortField == 'title' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.wow.instance{/lang}</a></th>

                {event name='columnHeads'}
            </tr>
            </thead>

            <tbody>
            {foreach from=$objects item=encounter}
                <tr class="jsGuildWowEncounterListRow encounterRow" data-member-id="{@$encounter->encounterID}">
                    <td class="columnIcon">
                        {if $__wcf->session->getPermission('admin.guild.canManageGames')}
                            <a href="{link application="guild" controller='WowEncounterEdit' id=$encounter->encounterID}{/link}"><span title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip icon icon16 fa-pencil"></span></a>
                            <span title="{lang}wcf.global.button.delete{/lang}" class="jsDeleteButton jsTooltip icon icon16 fa-times" data-object-id="{@$encounter->encounterID}" data-confirm-message-html="{lang __encode=true}guild.acp.game.wow.encounter.delete.sure{/lang}"></span>
                        {/if}

                        {event name='rowButtons'}
                    </td>
                    <td class="columnID columnEncounterID">{@$encounter->encounterID}</td>
                    <td class="columnTitle columnName">{$encounter->name}</td>
                    <td class="columnText columnInstance">{if $encounter->getInstance()}{$encounter->getInstance()->name}{else}&nbsp;{/if}</td>

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