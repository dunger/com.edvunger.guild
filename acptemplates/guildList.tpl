{include file='header' pageTitle='guild.acp.guild.list'}

{if $objects|count}
	<script data-relocate="true">
        $(function() {
            new WCF.Action.Toggle('guild\\data\\guild\\GuildAction', '.guildRow');
            new WCF.Action.Delete('guild\\data\\guild\\GuildAction', '.guildRow');
        });
	</script>
{/if}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}guild.acp.guild.list{/lang}</h1>
	</div>
		
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link application='guild' controller='GuildAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}guild.acp.guild.add{/lang}</span></a></li>
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks application="guild" controller="GameList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
	</div>
{/hascontent}

{if $objects|count}
	<div class="section tabularBox">
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnGameID{if $sortField == 'guildID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link application='guild' controller='GuildList'}pageNo={@$pageNo}&sortField=guildID&sortOrder={if $sortField == 'guildID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.member.gameID{/lang}</a></th>
					<th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='GuildList'}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.guild.name{/lang}</a></th>

					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=guild}
					<tr class="jsGuildListRow guildRow" data-game-id="{@$guild->guildID}">
						<td class="columnIcon">
                            {if $__wcf->session->getPermission('admin.guild.canManageGuild')}
                                <span class="icon icon16 fa-{if $guild->isActive}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if $guild->isActive}disable{else}enable{/if}{/lang}" data-object-id="{@$guild->guildID}"></span>
								<a href="{link application='guild' controller='GuildEdit' id=$guild->guildID}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 fa-pencil"></span></a>
								<span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$guild->guildID}" data-confirm-message-html="{lang __encode=true}guild.acp.guild.delete.sure{/lang}"></span>
                            {/if}
                            {if $__wcf->session->getPermission('admin.guild.canManageMember')}
								<a href="{link application='guild' controller='MemberList' id=$guild->guildID}{/link}" title="{lang}guild.acp.member.list{/lang}" class="jsTooltip"><span class="icon icon16 fa-user"></span></a>
                            {/if}

							{foreach from=$guild->getGame()->getApiClassGuildButtons() item=button}
								<a href="{link application="guild" controller=$button['controller'] id=$guild->guildID}{/link}"><span title="{lang}{$button['title']}{/lang}" class="jsTooltip icon icon16 {$button['icon']}"></span></a>
							{/foreach}

							{event name='rowButtons'}
						</td>
						<td class="columnID columnMemberID">{@$guild->guildID}</td>
						<td class="columnTitle columnName">{@$guild->name}</td>
						
						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	
	<footer class="contentFooter">
		{hascontent}
			<div class="paginationBottom">
				{content}{@$pagesLinks}{/content}
			</div>
		{/hascontent}
		
		<nav class="contentFooterNavigation">
			<ul>
				<li><a href="{link application='guild' controller='GuildAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}guild.acp.guild.add{/lang}</span></a></li>
				
				{event name='contentFooterNavigation'}
			</ul>
		</nav>
	</footer>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}