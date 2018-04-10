{include file='header' pageTitle='guild.acp.guild.list'}

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
					<th class="columnID columnGameID{if $sortField == 'gameID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link application='guild' controller='GameList'}pageNo={@$pageNo}&sortField=gameID&sortOrder={if $sortField == 'gameID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.member.gameID{/lang}</a></th>
					<th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link application='guild' controller='GameList'}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.acp.member.name{/lang}</a></th>

					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=guild}
					<tr class="jsGuildMemberListRow" data-game-id="{@$guild->guildID}">
						<td class="columnIcon">
                            {if $__wcf->session->getPermission('admin.guild.canManageGuild')}
								<span class="icon icon16 fa-{if $guild->isActive}check-{/if}square-o jsEnableButton jsTooltip pointer jsGuildButton" title="{lang}wcf.acp.user.{if $guild->isActive}disable{else}enable{/if}{/lang}" data-member-id="{@$guild->guildID}" data-enable-message="{lang}wcf.acp.user.enable{/lang}" data-disable-message="{lang}wcf.acp.user.disable{/lang}" data-enabled="{if $guild->isActive}true{else}false{/if}"></span>
								<a href="{link application='guild' controller='GuildEdit' id=$guild->guildID}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 fa-pencil"></span></a>
								<span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$guild->guildID}" data-confirm-message-html="{lang __encode=true}guild.acp.game.delete.sure{/lang}"></span>
                            {/if}
                            {if $__wcf->session->getPermission('admin.guild.canManageMember')}
								<a href="{link application='guild' controller='MemberList' id=$guild->guildID}{/link}" title="{lang}guild.acp.member.list{/lang}" class="jsTooltip"><span class="icon icon16 fa-user"></span></a>
                            {/if}

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