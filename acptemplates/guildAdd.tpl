{include file='header' pageTitle='guild.acp.guild.'|concat:$action}

{js application='guild' acp='true' file='WCF.ACP.Guild'}
<script> //data-relocate="true">
    $(function() {
        {if $__wcf->session->getPermission('admin.guild.canManageGuild')}
        WCF.ACP.Guild.Games.GameHandler.init();
        {/if}
    });
</script>
<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}guild.acp.guild.{$action}{/lang}</h1>
	</div>
</header>


{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link application='guild' controller='GuildAdd'}{/link}{else}{link application='guild' controller='GuildEdit' id=$guildID}{/link}{/if}">
	<div class="section">
		<dl{if $errorField == 'name'} class="formError"{/if}>
			<dt><label for="name">{lang}guild.acp.guild.name{/lang}</label></dt>
			<dd>
				<input type="text" id="name" name="name" value="{if $name}{@$name}{/if}" class="long" min="5">
			</dd>
		</dl>
		
		<dl{if $errorField == 'api'} class="formError"{/if}>
			<dt><label for="guildSelectApi">{lang}guild.acp.game{/lang}</label></dt>
			<dd>
				<select id="guildSelectApi" name="api">
					<option value="0">{lang}wcf.global.noSelection{/lang}</option>
					{foreach from=$availableGame item=game}
						<option value="{@$game->gameID}"{if $api == $game->gameID} selected{/if}>{@$game->name}</option>
					{/foreach}
				</select>
			</dd>
		</dl>
		
        <div id="fieldData" style="margin-top: 20px;">{@$fieldsData}</div>
		
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