{include file='header' pageTitle='guild.acp.game.instance.'|concat:$action}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.instance.{$action}{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='InstanceList'  id=$gameID}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}guild.acp.game.wow.instances{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>


{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='InstanceAdd' id=$gameID}{/link}{else}{link application='guild' controller='InstanceEdit' id=$instanceID}{/link}{/if}">
    <div class="section">
        <dl{if $errorField == 'name'} class="formError"{/if}>
            <dt><label for="name">{lang}guild.acp.game.wow.instance.name{/lang}</label></dt>
            <dd>
                <input type="text" id="name" name="name" value="{if $name}{@$name}{/if}" class="long" min="5">
            </dd>
        </dl>
        <dl{if $errorField == 'encounters'} class="formError"{/if}>
            <dt><label for="encounters">{lang}guild.acp.game.instance.encounters{/lang}</label></dt>
            <dd>
                <input type="number" id="encounters" name="encounters" value="{if $encounters}{@$encounters}{/if}" class="long" min="0">
            </dd>
        </dl>
        <dl>
            <dd>
                <label><input type="checkbox" id="isActive" name="isActive" value="1"{if $isActive} checked{/if}> {lang}guild.acp.guild.isActive{/lang}</label>
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