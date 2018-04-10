{include file='header' pageTitle='guild.acp.game.'|concat:$action}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.{$action}{/lang}</h1>
    </div>
</header>

{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='GameAdd'}{/link}{else}{link application='guild' controller='GameEdit' id=$gameID}{/link}{/if}">
    <div class="section">
        <dl{if $errorField == 'name'} class="formError"{/if}>
            <dt><label for="name">{lang}guild.acp.game.name{/lang}</label></dt>
            <dd>
                <input type="text" id="name" name="name" value="{if $name}{@$name}{/if}" class="long" min="5" disabled>
            </dd>
        </dl>
        <dl{if $errorField == 'apiKey'} class="formError"{/if}>
            <dt><label for="apiKey">{lang}guild.acp.game.apiKey{/lang}</label></dt>
            <dd>
                <input type="text" id="apiKey" name="apiKey" value="{if $apiKey}{@$apiKey}{/if}" class="long" min="1">
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