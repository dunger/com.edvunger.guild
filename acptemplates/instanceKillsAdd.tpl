{include file='header' pageTitle='guild.acp.game.instance.kills.'|concat:$action}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.instance.kills.{$action}{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='InstanceKillsList'  id=$guildID}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}guild.acp.game.instance.kills.settings{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>


{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='InstanceKillsAdd' id=$guildID}{/link}{else}{link application='guild' controller='InstanceKillsEdit' id=$instanceKillsID}{/link}{/if}">
    <div class="section">
        <dl{if $errorField == 'instanceID'} class="formError"{/if}>
            <dt><label for="instanceID">{lang}guild.acp.game.instance{/lang}</label></dt>
            <dd>
                <select id="instanceID" name="instanceID"{if $action == 'edit'} disabled{/if}>
                    <option value="0">{lang}wcf.global.noSelection{/lang}</option>
                    {foreach from=$instanceList item=instance}
                        <option value="{@$instance->instanceID}"{if $instanceID == $instance->instanceID} selected{/if}>{@$instance->name}</option>
                    {/foreach}
                </select>
            </dd>
        </dl>
        <dl{if $errorField == 'kills'} class="formError"{/if}>
            <dt><label for="kills">{lang}guild.acp.game.instance.kills{/lang}</label></dt>
            <dd>
                <input type="number" id="kills" name="kills" value="{if $kills}{@$kills}{/if}" class="small" min="0">
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