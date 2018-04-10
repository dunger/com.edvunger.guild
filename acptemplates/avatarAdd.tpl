{include file='header' pageTitle='guild.acp.avatar.'|concat:$action}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.avatar.{$action}{/lang}</h1>
    </div>
</header>


{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='AvatarAdd'}{/link}{else}{link application='guild' controller='AvatarEdit' id=$avatarID}{/link}{/if}">
    <div class="section">
        <dl{if $errorField == 'name'} class="formError"{/if}>
            <dt><label for="name">{lang}guild.acp.avatar.name{/lang}</label></dt>
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
        <dl{if $errorField == 'image'} class="formError"{/if}>
            <dt><label for="image">{lang}guild.acp.avatar.image{/lang}</label></dt>
            <dd>
                <input type="text" id="image" name="image" value="{if $image}{@$image}{/if}" class="long">
            </dd>
        </dl>
        <dl{if $errorField == 'autoAssignment'} class="formError"{/if}>
            <dt><label for="autoAssignment">{lang}guild.acp.avatar.autoAssignment{/lang}</label></dt>
            <dd>
                <input type="number" id="autoAssignment" name="autoAssignment" value="{if $autoAssignment}{@$autoAssignment}{/if}" class="long" min="0">
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