{include file='header' pageTitle='guild.acp.game.wow.encounter.'|concat:$action}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.wow.encounter.{$action}{/lang}</h1>
    </div>

    <nav class="contentHeaderNavigation">
        <ul>
            <li><a href="{link application='guild' controller='WowEncounterList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}guild.acp.game.wow.encounter{/lang}</span></a></li>

            {event name='contentHeaderNavigation'}
        </ul>
    </nav>
</header>


{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='WowEncounterAdd'}{/link}{else}{link application='guild' controller='WowEncounterEdit' id=$encounterID}{/link}{/if}">
    <div class="section">
        <dl{if $errorField == 'encounterID'} class="formError"{/if}>
            <dt><label for="encounterID">{lang}guild.acp.game.wow.encounter.id{/lang}</label></dt>
            <dd>
                <input type="number" id="encounterID" name="encounterID" value="{if $encounterID}{@$encounterID}{/if}" class="long" min="5">
                <small>{lang}guild.acp.game.wow.encounter.id.desc{/lang}</small>
            </dd>
        </dl>
        <dl{if $errorField == 'name'} class="formError"{/if}>
            <dt><label for="name">{lang}guild.acp.game.wow.encounter.name{/lang}</label></dt>
            <dd>
                <input type="text" id="name" name="name" value="{if $name}{@$name}{/if}" class="long" min="5">
            </dd>
        </dl>
        <dl{if $errorField == 'instanceID'} class="formError"{/if}>
            <dt><label for="instanceID">{lang}guild.acp.game.wow.instance{/lang}</label></dt>
            <dd>
                <select id="instanceID" name="instanceID">
                    <option value="0">{lang}wcf.global.noSelection{/lang}</option>
                    {foreach from=$instanceList->objects item=instance}
                        <option value="{@$instance->instanceID}"{if $instanceID == $instance->instanceID} selected{/if}>{@$instance->name}</option>
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