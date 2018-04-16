{capture assign='pageTitle'}{lang}guild.user.member{/lang}{@$guild->name}{/capture}

{capture assign='contentTitle'}{/capture}

{include file='header'}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{@$guild->name}</h1>
    </div>
</header>


<section class="section">
    <header class="sectionHeader sectionHeaderGuildMemberList">
        <ul class="containerBoxList quadrupleColumned">
            {foreach from=$roleList item=role}
                {if $role->roleID != 0}<li>{lang}{@$role->name}{/lang}</li>{/if}
            {/foreach}
        </ul>
    </header>


    <ul class="containerBoxList quadrupleColumned">
        {foreach from=$roleList item=role}
        <li>
            <ol class="containerList memberGuildList">
                {foreach from=$memberList item=member}
                    <li>
                    {if $member->roleID == $role->roleID}
                        <div class="box24">
                            <img src="{$__wcf->getPath('guild')}{$member->getAvatar()->image}" />
                            <div>
                                <ul class="inlineList">
                                    <li>{$member->name}</li>
                                    {if !$member->userID|is_null}<li>[<a href="{link controller='User' object=$member->getUserProfile()}{/link}" class="userLink" data-user-id="{@$member->userID}">{$member->getUserProfile()->username}</a>]</li>{/if}
                                </ul>
                                <p>{lang}{$member->getAvatar()->name}{/lang}</p>
                            </div>
                        </div>
                    {/if}
                    </li>
                {/foreach}
            </ol>
        </li>
        {/foreach}
    </ul>
</section>

{include file='footer'}