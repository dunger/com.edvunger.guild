{include file='header' pageTitle='guild.acp.game.wow.instance.'|concat:$action}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.wow.instance.{$action}{/lang}</h1>
    </div>
</header>


{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='WowInstanceAdd'}{/link}{else}{link application='guild' controller='WowInstanceEdit' id=$instanceID}{/link}{/if}">
    <div class="section">
        <dl{if $errorField == 'name'} class="formError"{/if}>
            <dt><label for="name">{lang}guild.acp.game.wow.instance.name{/lang}</label></dt>
            <dd>
                <input type="text" id="name" name="name" value="{if $name}{@$name}{/if}" class="long" min="5">
            </dd>
        </dl>
        <dl{if $errorField == 'title'} class="formError"{/if}>
            <dt><label for="title">{lang}guild.acp.game.wow.instance.title{/lang}</label></dt>
            <dd>
                <input type="text" id="title" name="title" value="{if $title}{@$title}{/if}" class="long" min="5">
            </dd>
        </dl>
        <dl{if $errorField == 'mapID'} class="formError"{/if}>
            <dt><label for="mapID">{lang}guild.acp.game.wow.instance.map{/lang}</label></dt>
            <dd>
                <input type="number" id="mapID" name="mapID" value="{if $mapID}{@$mapID}{/if}" class="long" min="0">
                <small>{lang}guild.acp.game.wow.instance.map.desc{/lang}</small>
            </dd>
        </dl>
        <dl{if $errorField == 'difficulty'} class="formError"{/if}>
            <dt><label for="difficulty">{lang}guild.acp.game.wow.instance.difficulty{/lang}</label></dt>
            <dd>
                <select id="difficulty" name="difficulty">
                    <option value="0"{if $difficulty == 0} selected{/if}>{lang}guild.acp.game.wow.instance.difficulty.normal{/lang}</option>
                    <option value="15"{if $difficulty == 15} selected{/if}>{lang}guild.acp.game.wow.instance.difficulty.heroic{/lang}</option>
                    <option value="30"{if $difficulty == 30} selected{/if}>{lang}guild.acp.game.wow.instance.difficulty.mythic{/lang}</option>
                </select>
            </dd>
        </dl>
        <dl{if $errorField == 'isRaid'} class="formError"{/if}>
            <dt><label for="isRaid">{lang}guild.acp.game.wow.instance.isRaid{/lang}</label></dt>
            <dd>
                <select id="isRaid" name="isRaid">
                    <option value="0"{if $isRaid == 0} selected{/if}>{lang}guild.acp.game.wow.instance.instance{/lang}</option>
                    <option value="1"{if $isRaid == 1} selected{/if}>{lang}guild.acp.game.wow.instance.raid{/lang}</option>
                </select>
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