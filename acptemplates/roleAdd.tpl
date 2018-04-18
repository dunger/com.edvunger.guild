{include file='header' pageTitle='guild.acp.role.'|concat:$action}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.avatar.{$action}{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='RoleList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}guild.acp.role.list{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>


{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='RoleAdd'}{/link}{else}{link application='guild' controller='RoleEdit' id=$roleID}{/link}{/if}">
    <div class="section">
        <dl{if $errorField == 'name'} class="formError"{/if}>
            <dt><label for="name">{lang}guild.acp.role.name{/lang}</label></dt>
            <dd>
                <input type="text" id="name" name="name" value="{if $name}{@$name}{/if}" class="long" min="5">
            </dd>
        </dl>
        <dl{if $errorField == 'gameID'} class="formError"{/if}>
            <dt><label for="gameID">{lang}guild.acp.game{/lang}</label></dt>
            <dd>
                <select id="gameID" name="gameID">
                    <option value="0">{lang}wcf.global.noSelection{/lang}</option>
                    {foreach from=$gameList item=game}
                        <option value="{@$game->gameID}"{if $gameID == $game->gameID} selected{/if}>{@$game->name}</option>
                    {/foreach}
                </select>
            </dd>
        </dl>
    </div>

    {event name='afterSections'}

    <div class="formSubmit">
        <input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
        {@SECURITY_TOKEN_INPUT_TAG}
    </div>
</form>

{include file='footer'}