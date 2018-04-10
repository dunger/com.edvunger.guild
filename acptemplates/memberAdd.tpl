{include file='header' pageTitle='guild.acp.member.'|concat:$action}

<script> //data-relocate="true">
    $(function() {
        require(['WoltLabSuite/Core/Ui/User/Search/Input'], function(UiUserSearchInput) {
            $('input[name=username]').each(function(){
                new UiUserSearchInput($(this).get(0));

            })
        });
    });
</script>

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.member.{$action}{/lang}</h1>
    </div>
</header>

{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='MemberAdd' id=$guild->guildID}{/link}{else}{link application='guild' controller='MemberEdit' id=$guild->guildID memberID=$memberID}{/link}{/if}">
    <div class="section">
        <dl{if $errorField == 'name'} class="formError"{/if}>
            <dt><label for="name">{lang}guild.acp.member.name{/lang}</label></dt>
            <dd>
                <input type="text" id="name" name="name" value="{if $name}{@$name}{/if}" class="long" min="3">
            </dd>
        </dl>
        <dl{if $errorField == 'username'} class="formError"{/if}>
            <dt><label for="name">{lang}guild.acp.member.user{/lang}</label></dt>
            <dd>
                <input type="text" id="username" name="username" value="{if $user}{@$user->username}{/if}" class="long" min="3">
            </dd>
        </dl>
        <dl{if $errorField == 'groupID'} class="formError"{/if}>
            <dt><label for="groupID">{lang}guild.acp.member.group{/lang}</label></dt>
            <dd>
                <select id="groupID" name="groupID">
                    <option value="0">{lang}wcf.global.noSelection{/lang}</option>
                    {foreach from=$userGroupList->objects item=group}
                        <option value="{@$group->groupID}"{if $groupID == $group->groupID} selected{/if}>{lang}{@$group->groupName}{/lang}</option>
                    {/foreach}
                </select>
            </dd>
        </dl>
        <dl{if $errorField == 'roleID'} class="formError"{/if}>
            <dt><label for="roleID">{lang}guild.acp.member.role{/lang}</label></dt>
            <dd>
                <select id="roleID" name="roleID">
                    <option value="0">{lang}wcf.global.noSelection{/lang}</option>
                    {foreach from=$roleList->objects item=role}
                        <option value="{@$role->roleID}"{if $roleID == $role->roleID} selected{/if}>{lang}{@$role->name}{/lang}</option>
                    {/foreach}
                </select>
            </dd>
        </dl>
        <dl{if $errorField == 'avatarID'} class="formError"{/if}>
            <dt><label for="avatarID">{lang}guild.acp.member.avatar{/lang}</label></dt>
            <dd>
                <select id="avatarID" name="avatarID">
                    <option value="0">{lang}wcf.global.noSelection{/lang}</option>
                    {foreach from=$avatarList->objects item=avatar}
                        <option value="{@$avatar->avatarID}"{if $avatarID == $avatar->avatarID} selected{/if}>{lang}{@$avatar->name}{/lang}</option>
                    {/foreach}
                </select>
            </dd>
        </dl>
        <dl{if $errorField == 'isMain'} class="formError"{/if}>
            <dt><label for="isMain">{lang}guild.acp.member.avatar{/lang}</label></dt>
            <dd>
                <select id="isMain" name="isMain">
                    <option value="0" {if $isMain == 0} selected{/if}>{lang}guild.acp.member.isMain.no{/lang}</option>
                    <option value="1" {if $isMain == 1} selected{/if}>{lang}guild.acp.member.isMain.yes{/lang}</option>
                </select>
            </dd>
        </dl>
        <dl>
            <dd>
                <label><input type="checkbox" id="isActive" name="isActive" value="1"{if $isActive} checked{/if}> {lang}guild.acp.guild.isActive{/lang}</label>
            </dd>
        </dl>

    {event name='afterSections'}

    </div>
    <div class="formSubmit">
        <input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
        {@SECURITY_TOKEN_INPUT_TAG}
    </div>
</form>
{include file='footer'}