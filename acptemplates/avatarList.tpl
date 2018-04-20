{include file='header' pageTitle='guild.acp.avatar.settings'}

{if $objects|count}
    <script data-relocate="true">
        $(function() {
            new WCF.Action.Toggle('guild\\data\\avatar\\AvatarAction', '.guildAvatarRow');
            new WCF.Action.Delete('guild\\data\\avatar\\AvatarAction', '.guildAvatarRow');
        });
    </script>
{/if}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.avatar.settings{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='AvatarAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}guild.acp.avatar.add{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>

{hascontent}
    <div class="paginationTop">
        {content}{pages print=true assign=pagesLinks application="guild" controller="AvatarList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox">
        <table class="table">
            <thead>
            <tr>
                <th class="columnID columnAvatarID{if $sortField == 'avatarID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link application='guild' controller='AvatarList'}pageNo={@$pageNo}&sortField=avatarID&sortOrder={if $sortField == 'avatarID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.avatar.avatarID{/lang}</a></th>
                <th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='AvatarList'}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.avatar.name{/lang}</a></th>
                <th class="columnText columnGame{if $sortField == 'gameID'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='AvatarList'}pageNo={@$pageNo}&sortField=gameID&sortOrder={if $sortField == 'gameID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.avatar.gameID{/lang}</a></th>
                <th class="columnText columnImage{if $sortField == 'image'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='AvatarList'}pageNo={@$pageNo}&sortField=image&sortOrder={if $sortField == 'image' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.avatar.image{/lang}</a></th>

                {event name='columnHeads'}
            </tr>
            </thead>

            <tbody>
            {foreach from=$objects item=avatar}
                <tr class="jsGuildAvatarListRow guildAvatarRow" data-member-id="{@$avatar->avatarID}">
                    <td class="columnIcon">
                        {if $__wcf->session->getPermission('admin.guild.canManageGames')}
                            <span class="icon icon16 fa-{if $avatar->isActive}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if $avatar->isActive}disable{else}enable{/if}{/lang}" data-object-id="{@$avatar->avatarID}"></span>
                            <a href="{link application="guild" controller='AvatarEdit' id=$avatar->avatarID}{/link}"><span title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip icon icon16 fa-pencil"></span></a>
                            <span title="{lang}wcf.global.button.delete{/lang}" class="jsDeleteButton jsTooltip icon icon16 fa-times" data-object-id="{@$avatar->avatarID}" data-confirm-message-html="{lang __encode=true}guild.acp.avatar.delete.sure{/lang}"></span>
                        {/if}

                        {event name='rowButtons'}
                    </td>
                    <td class="columnID columnAvatarID">{@$avatar->avatarID}</td>
                    <td class="columnTitle columnName">{lang}{$avatar->name}{/lang}</td>
                    <td class="columnText columnGame">{if $avatar->gameID}{$avatar->getGame()->name}{/if}</td>
                    <td class="columnText columnImage">{$avatar->image}</td>

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