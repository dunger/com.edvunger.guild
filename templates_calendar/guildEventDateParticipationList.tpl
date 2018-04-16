{if $event->enableParticipation && $participantList|count}
	{hascontent}
		<section class="section">
			<header class="sectionHeader">
				<h2 class="sectionTitle">{lang}calendar.event.participants{/lang} <span class="badge">{#$eventDate->participants}</span></h2>
				<p class="sectionDescription">{lang}calendar.event.participants.description{/lang}</p>
			</header>

			<header class="sectionHeader sectionHeaderParticipantGuild">
				<ul class="containerBoxList quadrupleColumned calendarParticipantList">
					{foreach from=$roleList item=role}
						{if $role->roleID != 0}<li>{lang}{@$role->name}{/lang}</li>{/if}
					{/foreach}
				</ul>
			</header>
			
			{if $eventDate->getParticipationEndTime() < TIME_NOW}<p class="info">{lang}calendar.event.participation.endTime.expired{/lang}</p>{/if}
			
			{content}
				{if $decisionYes > 0}
					<ul class="containerBoxList quadrupleColumned calendarParticipantList">
						{foreach from=$roleList item=role}
							<li>
								<ol class="containerList calendarParticipantGuildList">
									{foreach from=$participantList item=participant}
										{if $participant->decision == 'yes' && $participant->guildRoleID == $role->roleID && $participantData[$participant->userID]|isset}
											<li class="jsParticipant" style="border: none; padding: 0;">
												<div class="box24">
													{if $participantData[$participant->userID]|isset && $participantData[$participant->userID]->getAvatar() !== null}
														{if $participantData[$participant->userID]->buildLink()}
															<a href="{@$participantData[$participant->userID]->buildLink()}"><img src="{$__wcf->getPath('guild')}{@$participantData[$participant->userID]->getAvatar()->image}" /></a>
														{else}
															<img src="{$__wcf->getPath('guild')}{@$participantData[$participant->userID]->getAvatar()->image}" />
														{/if}
													{else}
														<a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{@$participant->getUserProfile()->getAvatar()->getImageTag(24)}</a>
													{/if}
													<div>
														<ul class="inlineList dotSeparated">
															<li>
																{if $participantData[$participant->userID]->buildLink()}
																	<a href="{@$participantData[$participant->userID]->buildLink()}">{@$participantData[$participant->userID]->name}</a>
																{else}
																	{@$participantData[$participant->userID]->name}
																{/if}
																[<a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{$participant->username}</a>{if $participant->participants > 1} <small>({lang participants=$participant->participants}calendar.event.participant.count{/lang})</small>{/if}]
															</li>
															{if !$eventDate->canRemoveParticipant()}
																<li class="jsOnly"><a class="jsDeleteButton jsTooltip" title="{lang}calendar.event.date.participation.removeParticipant{/lang}" data-confirm-message-html="{lang __encode=true}calendar.event.date.participation.removeParticipant.confirmMessage{/lang}" data-object-id="{@$participant->participationID}"><span class="icon icon16 fa-times"></span></a></li>
															{/if}
														</ul>
														<p>
															<small>{@$participant->decisionTime|time}</small>
														</p>
														<p>
															<small>{$participant->message}</small>
														</p>
													</div>
												</div>
											</li>
										{/if}
									{/foreach}
								</ol>
							</li>
						{/foreach}
					</ul>

					<ul class="containerBoxList quadrupleColumned calendarParticipantList">
						{foreach from=$participantList item=participant}
							{if $participant->decision == 'yes' && (!$participant->guildRoleID || !$participantData[$participant->userID]|isset)}
								<li class="jsParticipant">
									<div class="box24">
										<a href="{link controller='User' object=$participant->getUserProfile()}{/link}">{@$participant->getUserProfile()->getAvatar()->getImageTag(24)}</a>
										<div>
											<ul class="inlineList dotSeparated">
												<li><a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{$participant->username}</a>{if $participant->participants > 1} <small>({lang participants=$participant->participants}calendar.event.participant.count{/lang})</small>{/if}</li>
												<li><small>{@$participant->decisionTime|time}</small></li>
												{if $eventDate->canRemoveParticipant()}
													<li class="jsOnly"><a class="jsDeleteButton jsTooltip" title="{lang}calendar.event.date.participation.removeParticipant{/lang}" data-confirm-message-html="{lang __encode=true}calendar.event.date.participation.removeParticipant.confirmMessage{/lang}" data-object-id="{@$participant->participationID}"><span class="icon icon16 fa-times"></span></a></li>
												{/if}
											</ul>
											<p>
												<small>{$participant->message}</small>
											</p>
										</div>
									</div>
								</li>
							{/if}
						{/foreach}
					</ul>
				{/if}
			{/content}
		</section>
	{/hascontent}
	
	{hascontent}
		<section class="section">
			<header class="sectionHeader">
				<h2 class="sectionTitle">{lang}calendar.event.undecidedParticipants{/lang} <span class="badge">{#$decisionMaybe}</span></h2>
				<p class="sectionDescription">{lang}calendar.event.undecidedParticipants.description{/lang}</p>
			</header>

			<header class="sectionHeader sectionHeaderParticipantGuild">
				<ul class="containerBoxList quadrupleColumned calendarParticipantList">
					{foreach from=$roleList item=role}
						{if $role->roleID != 0}<li>{lang}{@$role->name}{/lang}</li>{/if}
					{/foreach}
				</ul>
			</header>
			
			{content}
				{if $decisionMaybe > 0}
					<ul class="containerBoxList quadrupleColumned calendarParticipantList">
						{foreach from=$roleList item=role}
							<li>
								<ol class="containerList calendarParticipantGuildList">
									{foreach from=$participantList item=participant}
										{if $participant->decision == 'maybe' && $participant->guildRoleID == $role->roleID && $participantData[$participant->userID]|isset}
											<li class="jsParticipant" style="border: none; padding: 0;">
												<div class="box24">
													{if $participantData[$participant->userID]|isset && $participantData[$participant->userID]->getAvatar() !== null}
														{if $participantData[$participant->userID]->buildLink()}
															<a href="{@$participantData[$participant->userID]->buildLink()}"><img src="{$__wcf->getPath('guild')}{@$participantData[$participant->userID]->getAvatar()->image}" /></a>
														{else}
															<img src="{$__wcf->getPath('guild')}{@$participantData[$participant->userID]->getAvatar()->image}" />
														{/if}
													{else}
														<a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{@$participant->getUserProfile()->getAvatar()->getImageTag(24)}</a>
													{/if}
													<div>
														<ul class="inlineList dotSeparated">
															<li>
																{if $participantData[$participant->userID]->buildLink()}
																	<a href="{@$participantData[$participant->userID]->buildLink()}">{@$participantData[$participant->userID]->name}</a>
																{else}
																	{@$participantData[$participant->userID]->name}
																{/if}
																[<a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{$participant->username}</a>{if $participant->participants > 1} <small>({lang participants=$participant->participants}calendar.event.participant.count{/lang})</small>{/if}]
															</li>
															{if !$eventDate->canRemoveParticipant()}
																<li class="jsOnly"><a class="jsDeleteButton jsTooltip" title="{lang}calendar.event.date.participation.removeParticipant{/lang}" data-confirm-message-html="{lang __encode=true}calendar.event.date.participation.removeParticipant.confirmMessage{/lang}" data-object-id="{@$participant->participationID}"><span class="icon icon16 fa-times"></span></a></li>
															{/if}
														</ul>
														<p>
															<small>{@$participant->decisionTime|time}</small>
														</p>
														<p>
															<small>{$participant->message}</small>
														</p>
													</div>
											</li>
										{/if}
									{/foreach}
								</ol>
							</li>
						{/foreach}
					</ul>
					
					<ul class="containerBoxList quadrupleColumned calendarParticipantList">
						{foreach from=$participantList item=participant}
							{if $participant->decision == 'maybe' && (!$participant->guildRoleID || !$participantData[$participant->userID]|isset)}
								<li class="jsParticipant">
									<div class="box24">
										<a href="{link controller='User' object=$participant->getUserProfile()}{/link}">{@$participant->getUserProfile()->getAvatar()->getImageTag(24)}</a>
										<div>
											<ul class="inlineList dotSeparated">
												<li><a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{$participant->username}</a>{if $participant->participants > 1} <small>({lang participants=$participant->participants}calendar.event.participant.count{/lang})</small>{/if}</li>
												<li><small>{@$participant->decisionTime|time}</small></li>
												{if $eventDate->canRemoveParticipant()}
													<li class="jsOnly"><a class="jsDeleteButton jsTooltip" title="{lang}calendar.event.date.participation.removeParticipant{/lang}" data-confirm-message-html="{lang __encode=true}calendar.event.date.participation.removeParticipant.confirmMessage{/lang}" data-object-id="{@$participant->participationID}"><span class="icon icon16 fa-times"></span></a></li>
												{/if}
											</ul>
											<p>
												<small>{$participant->message}</small>
											</p>
										</div>
									</div>
								</li>
							{/if}
						{/foreach}
					</ul>
				{/if}
			{/content}
		</section>
	{/hascontent}
	
	{hascontent}
		<section class="section">
			<header class="sectionHeader">
				<h2 class="sectionTitle">{lang}calendar.event.noParticipants{/lang} <span class="badge">{#$decisionNo}</span></h2>
				<p class="sectionDescription">{lang}calendar.event.noParticipants.description{/lang}</p>
			</header>

			<header class="sectionHeader sectionHeaderParticipantGuild">
				<ul class="containerBoxList quadrupleColumned calendarParticipantList">
					{foreach from=$roleList item=role}
						{if $role->roleID != 0}<li>{lang}{@$role->name}{/lang}</li>{/if}
					{/foreach}
				</ul>
			</header>
			
			{content}
				{if $decisionNo > 0}
					<ul class="containerBoxList quadrupleColumned calendarParticipantList">
						{foreach from=$roleList item=role}
							<li>
								<ol class="containerList calendarParticipantGuildList">
									{foreach from=$participantList item=participant}
										{if $participant->decision == 'no' && $participant->guildRoleID == $role->roleID && $participantData[$participant->userID]|isset}
											<li class="jsParticipant" style="border: none; padding: 0;">
												<div class="box24">
													{if $participantData[$participant->userID]|isset && $participantData[$participant->userID]->getAvatar() !== null}
														{if $participantData[$participant->userID]->buildLink()}
															<a href="{@$participantData[$participant->userID]->buildLink()}"><img src="{$__wcf->getPath('guild')}{@$participantData[$participant->userID]->getAvatar()->image}" /></a>
														{else}
															<img src="{$__wcf->getPath('guild')}{@$participantData[$participant->userID]->getAvatar()->image}" />
														{/if}
													{else}
														<a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{@$participant->getUserProfile()->getAvatar()->getImageTag(24)}</a>
													{/if}
													<div>
														<ul class="inlineList dotSeparated">
															<li>
																{if $participantData[$participant->userID]->buildLink()}
																	<a href="{@$participantData[$participant->userID]->buildLink()}">{@$participantData[$participant->userID]->name}</a>
																{else}
																	{@$participantData[$participant->userID]->name}
																{/if}
																[<a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{$participant->username}</a>{if $participant->participants > 1} <small>({lang participants=$participant->participants}calendar.event.participant.count{/lang})</small>{/if}]
															</li>
															{if !$eventDate->canRemoveParticipant()}
																<li class="jsOnly"><a class="jsDeleteButton jsTooltip" title="{lang}calendar.event.date.participation.removeParticipant{/lang}" data-confirm-message-html="{lang __encode=true}calendar.event.date.participation.removeParticipant.confirmMessage{/lang}" data-object-id="{@$participant->participationID}"><span class="icon icon16 fa-times"></span></a></li>
															{/if}
														</ul>
														<p>
															<small>{@$participant->decisionTime|time}</small>
														</p>
														<p>
															<small>{$participant->message}</small>
														</p>
													</div>
											</li>
										{/if}
									{/foreach}
								</ol>
							</li>
						{/foreach}
					</ul>
					
					<ul class="containerBoxList quadrupleColumned calendarParticipantList">
						{foreach from=$participantList item=participant}
							{if $participant->decision == 'no' && (!$participant->guildRoleID || !$participantData[$participant->userID]|isset)}
								<li class="jsParticipant">
									<div class="box24">
										<a href="{link controller='User' object=$participant->getUserProfile()}{/link}">{@$participant->getUserProfile()->getAvatar()->getImageTag(24)}</a>
										<div>
											<ul class="inlineList dotSeparated">
												<li><a href="{link controller='User' object=$participant->getUserProfile()}{/link}" class="userLink" data-user-id="{@$participant->userID}">{$participant->username}</a>{if $participant->participants > 1} <small>({lang participants=$participant->participants}calendar.event.participant.count{/lang})</small>{/if}</li>
												<li><small>{@$participant->decisionTime|time}</small></li>
												{if $eventDate->canRemoveParticipant()}
													<li class="jsOnly"><a class="jsDeleteButton jsTooltip" title="{lang}calendar.event.date.participation.removeParticipant{/lang}" data-confirm-message-html="{lang __encode=true}calendar.event.date.participation.removeParticipant.confirmMessage{/lang}" data-object-id="{@$participant->participationID}"><span class="icon icon16 fa-times"></span></a></li>
												{/if}
											</ul>
											<p>
												<small>{$participant->message}</small>
											</p>
										</div>
									</div>
								</li>
							{/if}
						{/foreach}
					</ul>
				{/if}
			{/content}
		</section>
	{/hascontent}
{/if}

{hascontent}
	<section class="section">
		<header class="sectionHeader">
			<h2 class="sectionTitle">{lang}guild.user.calendar.missingParticipants{/lang}</h2>
			<p class="sectionDescription">{lang}guild.user.calendar.missingParticipants.description{/lang}</p>
		</header>
        {content}
			<ul class="containerBoxList quadrupleColumned calendarParticipantList">
                {foreach from=$missingParticipantList item=participant}
					<li class="jsParticipant">
						<div class="box24">
                            {if $participant->userID}
								{if $participant->buildLink()}
									<a href="{@$participant->buildLink()}"><img src="{$__wcf->getPath('guild')}{@$participant->getAvatar()->image}" /></a>
								{else}
									<img src="{$__wcf->getPath('guild')}{@$participant->getAvatar()->image}" />
								{/if}
								<ul class="inlineList dotSeparated">
									<li>
										{if $participant->buildLink()}
											<a href="{@$participant->buildLink()}">{$participant->name}</a>
										{else}
											{$participant->name}
										{/if}
										[<a href="{link controller='User' object=$participant->profile}{/link}" class="userLink" data-user-id="{@$participant->userID}">{$participant->profile->username}</a>]
									</li>
								</ul>

                            {else}
                                {if $participant->getAvatar() !== null}<img src="{$__wcf->getPath('guild')}{@$participant->getAvatar()->image}" />{/if}
								<div>{@$participant->name}</div>
                            {/if}
						</div>
					</li>
                {/foreach}
			</ul>
        {/content}
	</section>
{/hascontent}