{include file='header' pageTitle='guild.acp.game.settings'}

{if $objects|count}
    <script data-relocate="true">
        $(function() {
            new WCF.Action.Toggle('guild\\data\\game\\GameAction', '.guildGameRow');
            new WCF.Action.Delete('guild\\data\\game\\GameAction', '.guildGameRow');
        });
    </script>
{/if}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.settings{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>

{hascontent}
    <div class="paginationTop">
        {content}{pages print=true assign=pagesLinks application="guild" controller="GameList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox">
        <table class="table">
            <thead>
            <tr>
                <th class="columnID columnEncounterID{if $sortField == 'gameID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link application='guild' controller='GameList'}pageNo={@$pageNo}&sortField=gameID&sortOrder={if $sortField == 'gameID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.gameID{/lang}</a></th>
                <th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='GameList'}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.game.name{/lang}</a></th>

                {event name='columnHeads'}
            </tr>
            </thead>

            <tbody>
            {foreach from=$objects item=game}
                <tr class="jsGuildGameListRow guildGameRow" data-member-id="{@$game->gameID}">
                    <td class="columnIcon">
                        {if $__wcf->session->getPermission('admin.guild.canManageGames')}
                            <span class="icon icon16 fa-{if $game->isActive}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if $game->isActive}disable{else}enable{/if}{/lang}" data-object-id="{@$game->gameID}"></span>
                            <a href="{link application="guild" controller='GameEdit' id=$game->gameID}{/link}"><span title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip icon icon16 fa-pencil"></span></a>

                            {foreach from=$game->getApiClassButtons() item=button}
                                <a href="{link application="guild" controller=$button['controller'] id=$game->gameID}{/link}"><span title="{lang}{$button['title']}{/lang}" class="jsTooltip icon icon16 {$button['icon']}"></span></a>
                            {/foreach}
                        {/if}

                        {event name='rowButtons'}
                    </td>
                    <td class="columnID columnGameID">{@$game->gameID}</td>
                    <td class="columnTitle columnName">{$game->name}</td>

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