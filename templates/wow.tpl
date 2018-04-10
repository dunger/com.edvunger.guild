{capture assign='pageTitle'}{lang}guild.user.member{/lang}{@$guild->name}{/capture}

{capture assign='contentTitle'}{/capture}

{include file='header'}

{if $memberList|count}
    <div class="section tabularBox messageGroupList guildMemberGroupList">
        <ol class="tabularList">
            <li class="tabularListRow tabularListRowHead">
                <ol class="tabularListColumns">
                    <li class="columnSubject{if $sortField === 'name'} active {@$sortOrder}{/if}"><a rel="nofollow" href="{link application='guild' controller='details' object=$guild}sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.user.name{/lang}</a></li>
                    <li class="columnStats{if $sortField === 'role'} active {@$sortOrder}{/if}"><a rel="nofollow" href="{link application='guild' controller='details' object=$guild}sortField=role&sortOrder={if $sortField == 'role' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.user.role{/lang}</a></li>
                    <li class="columnStats{if $sortField === 'gear'} active {@$sortOrder}{/if}"><a rel="nofollow" href="{link application='guild' controller='details' object=$guild}sortField=gear&sortOrder={if $sortField == 'gear' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.user.gear{/lang}</a></li>
                    <li class="columnStats{if $sortField === 'al'} active {@$sortOrder}{/if}"><a rel="nofollow" href="{link application='guild' controller='details' object=$guild}sortField=al&sortOrder={if $sortField == 'al' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.user.weapon{/lang}</a></li>
                    <li class="columnStats{if $sortField === 'ap'} active {@$sortOrder}{/if}"><a rel="nofollow" href="{link application='guild' controller='details' object=$guild}sortField=ap&sortOrder={if $sortField == 'ap' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}guild.user.artefaktweapon{/lang}</a></li>
                </ol>
            </li>

            {foreach from=$memberList item=member}
                <li class="tabularListRow">
                    <ol id="member{@$member->memberID}" class="tabularListColumns">
                        <li class="columnIcon columnAvatar">
                            <ul class="inlineList">
                                <li><img src="https://render-eu.worldofwarcraft.com/character/{@$member->thumbnail}" style="width: 48px; height: 48px" alt="" class="userAvatarImage"></li>
                                <li><img src="{$__wcf->getPath('guild')}{@$member->getAvatar()->image}"  style="width: 48px; height: 48px" alt=""  class="" /></li>
                            </ul>
                        </li>
                        <li class="columnSubject memberName">
                            <h3>
                                <ul class="inlineList dotSeparated">
                                    <li class="messageGroupAuthor"><a href="{$member->buildLink()}">{@$member->name}</a></li>
                                    {if $member->race}<li class="messageGroupAuthor">{lang}{/lang}</li>{/if}
                                </ul>
                            </h3>
                            <ul class="inlineList dotSeparated small messageGroupInfo">
                                {if $member->getUserProfile()}<li class="messageGroupAuthor"><a href="{link application='wcf' controller='User' object=$member->getUserProfile()}{/link}" class="userLink" data-user-id="{@$member->userID}">{@$member->profile->username}</a></li>{/if}
                                <li class="mageGroupTime">{if $member->getTime('lastModified')}{@$member->getTime('lastModified')|time}{/if}</li>
                            </ul>
                            <ul class="messageGroupInfoMobile">
                                {if $member->getUserProfile()}
                                    <li class="messageGroupAuthor"><a href="{link application='wcf' controller='User' object=$member->getUserProfile()}{/link}" class="userLink" data-user-id="{@$member->userID}">{@$member->profile->username}</a></li>
                                {/if}
                            </ul>
                        </li>

                        <li class="columnStats">{lang}{if $member->getRole()}{@$member->getRole()->name}{else}&nbsp;{/if}{/lang}</li>

                        <li class="columnStats">
                            <dl class="plain statsDataList">
                                <dt>{@$member->getStats('iLevel')}</dt>
                                <dd>&nbsp;</dd>
                            </dl>
                            <dl class="plain statsDataList">
                                <dt>{@$member->getStatsDiff('iLevel', false, true)}</dt>
                                <dd>&nbsp;</dd>
                            </dl>

                            <div class="messageGroupListStatsSimple">{@$member->getStats('iLevel')}</div>
                        </li>

                        <li class="columnStats">
                            <dl class="plain statsDataList">
                                <dt>{@$member->getStats('artefactweaponLevel')}</dt>
                                <dd>&nbsp;</dd>
                            </dl>
                            <dl class="plain statsDataList">
                                <dt>{@$member->getStatsDiff('artefactweaponLevel')}</dt>
                                <dd>&nbsp;</dd>
                            </dl>

                            <div class="messageGroupListStatsSimple">{@$member->getStats('artefactweaponLevel')}</div>
                        </li>

                        <li class="columnStats">
                            <dl class="plain statsDataList">
                                <dt>{@$member->getStats('artefactPower', true)}</dt>
                                <dd>&nbsp;</dd>
                            </dl>
                            <dl class="plain statsDataList">
                                <dt>{@$member->getStatsDiff('artefactPower', true)}</dt>
                                <dd>&nbsp;</dd>
                            </dl>

                            <div class="messageGroupListStatsSimple">{@$member->getStatsDiff('artefactPower', true)}</div>
                        </li>
                    </ol>
                </li>
            {/foreach}
        </ol>
    </div>
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}