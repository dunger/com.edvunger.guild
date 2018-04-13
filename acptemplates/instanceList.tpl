{include file='header' pageTitle='guild.acp.game.instance.settings'}

{if $objects|count}
    <script data-relocate="true">
        $(function() {
            new WCF.Action.Toggle('guild\\data\\instance\\InstanceAction', '.guildInstanceRow');
            new WCF.Action.Delete('guild\\data\\instance\\InstanceAction', '.guildInstanceRow');
        });
    </script>
{/if}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.instance.settings{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='InstanceAdd' id=$gameID}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}guild.acp.game.instance.add{/lang}</span></a></li>
            <li><a href="{link application='guild' controller='GameList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}guild.acp.games{/lang}</span></a></li>

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
                <th class="columnID columnInstanceID{if $sortField == 'instanceID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link application='guild' controller='InstanceList' id=$gameID}pageNo={@$pageNo}&sortField=instanceID&sortOrder={if $sortField == 'instanceID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.instance.instanceID{/lang}</a></th>
                <th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='InstanceList' id=$gameID}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.instance.name{/lang}</a></th>
                <th class="columnText columnEncounters{if $sortField == 'encounters'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='InstanceList' id=$gameID}pageNo={@$pageNo}&sortField=encounters&sortOrder={if $sortField == 'encounters' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.instance.encounters{/lang}</a></th>

                {event name='columnHeads'}
            </tr>
            </thead>

            <tbody>
            {foreach from=$objects item=instance}
                <tr class="jsGuildInstanceListRow guildInstanceRow" data-member-id="{@$instance->instanceID}">
                    <td class="columnIcon">
                        {if $__wcf->session->getPermission('admin.guild.canManageGames')}
                            <span class="icon icon16 fa-{if $instance->isActive}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if $instance->isActive}disable{else}enable{/if}{/lang}" data-object-id="{@$instance->instanceID}"></span>
                            <a href="{link application="guild" controller='InstanceEdit' id=$instance->instanceID}{/link}"><span title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip icon icon16 fa-pencil"></span></a>
                            <span title="{lang}wcf.global.button.delete{/lang}" class="jsDeleteButton jsTooltip icon icon16 fa-times" data-object-id="{@$instance->instanceID}" data-confirm-message-html="{lang __encode=true}guild.acp.game.wow.instance.delete.sure{/lang}"></span>
                        {/if}

                        {event name='rowButtons'}
                    </td>
                    <td class="columnID columnInstanceID">{@$instance->instanceID}</td>
                    <td class="columnTitle columnName">{$instance->name}</td>
                    <td class="columnText columnEncounters">{$instance->encounters}</td>

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