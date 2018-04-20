{include file='header' pageTitle='guild.acp.member.list'}

{js application='guild' acp='true' file='WCF.ACP.Guild'}
<script data-relocate="true">
    $(function() {
        {if $__wcf->session->getPermission('admin.guild.canManageMember')}
        WCF.ACP.Guild.Member.EnableHandler.init({$guild->guildID});

        require(['WoltLabSuite/Core/Ui/User/Search/Input'], function(UiUserSearchInput) {
            $('input[name=username]').each(function(){
                new UiUserSearchInput($(this).get(0));

            })
        });

        $('.columnUserName').dblclick(function() {
            $(this).children('a').addClass('invisible');
            $(this).children('.inputAddon').removeClass('invisible');
        });
        {/if}
    });
</script>

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.member.list.from{/lang} {$guild->name}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            {if $editable}<li><a href="{link application='guild' controller='MemberAdd' id=$guild->guildID}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}guild.acp.member.add{/lang}</span></a></li>{/if}
            <li><a href="{link application='guild' controller='GuildList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}guild.acp.guild.list{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>

{hascontent}
    <div class="paginationTop">
        {content}{pages print=true assign=pagesLinks application="guild" controller="MemberList" object=$guild link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox">
        <table class="table">
            <thead>
            <tr>
                <th class="columnID columnMemberID{if $sortField == 'memberID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link application='guild' controller='MemberList' object=$guild}pageNo={@$pageNo}&sortField=memberID&sortOrder={if $sortField == 'memberID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.member.memberID{/lang}</a></th>
                <th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='MemberList' object=$guild}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.member.name{/lang}</a></th>
                <th class="columnText columnUsername{if $sortField == 'userID'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='MemberList' object=$guild}pageNo={@$pageNo}&sortField=userID&sortOrder={if $sortField == 'userID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.member.user{/lang}</a></th>
                {if !$editable}<th class="columnText columnIsApiActive{if $sortField == 'isApiActive'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='MemberList' object=$guild}pageNo={@$pageNo}&sortField=isApiActive&sortOrder={if $sortField == 'isApiActive' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.member.isApiActive{/lang}</a></th>{/if}

                {event name='columnHeads'}
            </tr>
            </thead>

            <tbody>
            {foreach from=$objects item=member}
                <tr class="jsGuildMemberListRow guildMemberRow" data-member-id="{@$member->memberID}">
                    <td class="columnIcon">
                        {if $__wcf->session->getPermission('admin.guild.canManageMember')}<span class="icon icon16 fa-{if $member->isActive}check-{/if}square-o jsEnableButton jsTooltip pointer {if $member->isActive && $member->isApiActive}disabled{else}jsGuildButton{/if}" title="{lang}wcf.acp.user.{if $member->isActive}disable{else}enable{/if}{/lang}" data-member-id="{@$member->memberID}" data-enable-message="{lang}wcf.acp.user.enable{/lang}" data-disable-message="{lang}wcf.acp.user.disable{/lang}" data-enabled="{if !$member->isActive}false{else}true{/if}"></span>{/if}
                        {if $editable}<a href="{link application="guild" controller='MemberEdit' id=$guild->guildID memberID=$member->memberID}{/link}"><span title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip icon icon16 fa-pencil"></span></a>{/if}
                        {event name='rowButtons'}
                    </td>
                    <td class="columnID columnMemberID">{@$member->memberID}</td>
                    <td class="columnTitle columnName">{$member->name}</td>
                    <td class="columnText columnUsername">
                        <div class="columnUserNameText{if !$member->userID} invisible{/if}">
                            {if $member->userID}
                                {if $__wcf->session->getPermission('admin.guild.canManageMember')}<span class="icon icon16 fa-remove jsGuildButton jsDeleteButton jsTooltip pointer" title="{lang}wcf.acp.user.delete{/lang}" data-member-id="{@$member->memberID}" data-deleteuser="true"></span>{/if}
                                <span class="columnUserNameTextData">{if $member->isMain}{lang}guild.acp.member.isMain.yes{/lang}{else}{lang}guild.acp.member.isMain.no{/lang}{/if}: <a title="{lang}wcf.acp.user.edit{/lang}" href="{link controller='UserEdit' id=$member->userID}{/link}">{@$member->getUserProfile()->username} {if $member->getRole()}[{lang}{@$member->getRole()->name}{/lang}]{/if}</a></span>
                            {else}
                                {if $__wcf->session->getPermission('admin.guild.canManageMember')}<span class="icon icon16 fa-remove jsGuildButton jsDeleteButton jsTooltip pointer" title="{lang}wcf.acp.user.delete{/lang}" data-member-id="{@$member->memberID}" data-deleteuser="true"></span>{/if}
                                <span class="columnUserNameTextData"><a title="{lang}wcf.acp.user.edit{/lang}" href=""></a></span>
                            {/if}
                        </div>
                        {if $__wcf->session->getPermission('admin.guild.canManageMember')}
                            <div class="inputAddon columnUserNameForm {if $member->userID}invisible{/if}">
                                <input type="text" value="{if $member->userID}{@$member->profile->username}{/if}" name="username" class="medium" data-empty="true" data-member-id="{@$member->memberID}">
                                {if !$rankList->objects|empty}
                                <select class="inputSuffix" name="rankID" data-rank-member-id="{@$member->memberID}">
                                    <option value="0" {if $member->rankID == null} selected{/if}>{lang}guild.acp.rank.selectRank{/lang}</option>
                                    {foreach from=$rankList->objects item=rank}
                                        <option value="{@$rank->rankID}"{if $rank->rankID == $member->rankID} selected{/if}>{lang}{@$rank->getName()}{/lang}</option>
                                    {/foreach}
                                </select>
                                {/if}
                                <select class="inputSuffix" name="roleID" data-role-member-id="{@$member->memberID}">
                                    <option value="0"{if $member->roleID == null} selected{/if}>{lang}guild.user.role.none{/lang}</option>
                                    {if !$roleList->objects|empty}
                                        {foreach from=$roleList->objects item=role}
                                            <option value="{@$role->roleID}"{if $role->roleID == $member->roleID} selected{/if}>{lang}{$role->name}{/lang}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                                <select class="inputSuffix" name="groupID" data-group-member-id="{@$member->memberID}">
                                    <option value="0" {if $member->groupID == null} selected{/if}>{lang}guild.acp.member.noGroup{/lang}</option>
                                    {if !$userGroupList->objects|empty}
                                        {foreach from=$userGroupList->objects item=group}
                                            <option value="{@$group->groupID}"{if $group->groupID == $member->groupID} selected{/if}>{lang}{@$group->getName()}{/lang}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                                <select class="inputSuffix" name="isMain" data-ismain-member-id="{@$member->memberID}">
                                    <option value="0" {if $member->isMain == 0} selected{/if}>{lang}guild.acp.member.isMain.no{/lang}</option>
                                    <option value="1" {if $member->isMain == 1} selected{/if}>{lang}guild.acp.member.isMain.yes{/lang}</option>
                                </select>
                                <a class="inputSuffix button jsGuildButton" data-member-id="{@$member->memberID}" data-setuser="true"><span class="icon icon16 fa-save"></span></a>
                            </div>
                        {/if}
                    </td>
                    {if !$editable}<td class="columnText columnIsApiActive">{@$member->isApiActive}</td>{/if}

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