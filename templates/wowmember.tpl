{capture assign='pageTitle'}{@$member->name}{/capture}

{capture assign='contentTitle'}{/capture}

{include file='header'}

<style>
    body {
        background: url(https://render-eu.worldofwarcraft.com/character/{@$background}) no-repeat top center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>

<div class="section tabMenuContainer guildMemberDetails" data-active="" data-store="activeTabMenuItem" data-is-legacy="true">
    <nav class="tabMenu">
        <ul>
            <li data-name="overview" aria-controls="overview"><a href="#overview">{lang}guild.user.header.overview{/lang}</a></li>
            <li data-name="instance" aria-controls="instance"><a href="#instance">{lang}guild.user.header.instance{/lang}</a></li>
        </ul>
    </nav>
    <div id="details" class="tabMenuContent" data-name="overview">
        <aside class="sidebar boxesSidebarLeft">
            <div class="boxContainer">
                <section class="box">
                    <h2 class="boxTitle">{@$member->name}</h2>

                    <nav class="boxContent">
                        <dl class="plain dataList containerContent small">
                            <dt>{lang}guild.user.level{/lang}</dt>
                            <dd>{@$member->getStats('level')}</dd>
                            <dt>{lang}guild.user.role{/lang}</dt>
                            <dd>{lang}{if $member->getRole()}{@$member->getRole()->name}{else}guild.user.role.noselected{/if}{/lang}</dd>
                            <dt>{lang}guild.user.race{/lang}</dt>
                            <dd>{lang}guild.user.race.{@$member->getStats('race')}{/lang}</dd>
                            <dt>{lang}guild.user.class{/lang}</dt>
                            <dd>{lang}{@$member->getAvatar()->name}{/lang}</dd>
                            <dt>{lang}guild.user.ilevel{/lang}</dt>
                            <dd>{@$member->getStats('iLevel')}</dd>
                        </dl>
                    </nav>
                </section>
            </div>
        </aside>

        <div class="contentMiddle"></div>

        <aside class="sidebar boxesSidebarRight">
            <div class="boxContainer">
                <section class="box">
                    <h2 class="boxTitle">{lang}guild.user.thisweek{/lang}</h2>

                    <nav class="boxContent">
                        <dl class="plain dataList containerContent small">
                            <dt>{lang}guild.user.ilevel{/lang}</dt>
                            <dd>{@$member->getStats('iLevel')} ({$member->getStatsDiff('iLevel', true)})</dd>
                            <dt>{lang}guild.user.weapon{/lang}</dt>
                            <dd>{@$member->getStats('artefactweaponLevel')} ({$member->getStatsDiff('artefactweaponLevel')})</dd>
                            <dt>{lang}guild.user.artefaktpower{/lang}</dt>
                            <dd>{@$member->getStats('artefactPower', true)} ({$member->getStatsDiff('artefactPower', true)})</dd>
                            <dt>{lang}guild.user.worldquest{/lang}</dt>
                            <dd>{@$member->getStats('worldQuest')} ({$member->getStatsDiff('worldQuest')})</dd>
                        </dl>
                    </nav>
                </section>
            </div>
        </aside>
    </div>
    <div id="instance" class="tabMenuContent" data-name="instance">
        <aside class="sidebar boxesSidebarLeft">
            <div class="boxContainer">
                {foreach from=$raidStatisics item=raid}
                    <section class="box">
                        <h2 class="boxTitle">{@$raid['instanceTitle']}</h2>

                        <nav class="boxContent">
                            <dl class="plain dataList containerContent small">
                                {foreach from=$raid['data'] item=boss}
                                    <dt>{@$boss->encounterName}</dt>
                                    <dd>{@$boss->bossKills}</dd>
                                {/foreach}
                            </dl>
                        </nav>
                    </section>
                {/foreach}
            </div>
        </aside>

        <div class="contentMiddle">

        </div>

        <aside class="sidebar boxesSidebarRight">
            <div class="boxContainer">
                <section class="box">
                    <h2 class="boxTitle">{lang}guild.user.dungeon{/lang}</h2>

                    <nav class="boxContent">
                        <dl class="plain dataList containerContent small">
                            {foreach from=$instanceStatistics item=instance}
                                <dt>{@$instance->instanceTitle}</dt>
                                <dd>{@$instance->kills}</dd>
                            {/foreach}
                        </dl>
                    </nav>
                </section>

                <section class="box">
                    <h2 class="boxTitle">{lang}guild.user.dungeonplus{/lang}</h2>

                    <nav class="boxContent">
                        <dl class="plain dataList containerContent small">
                            <dt>+2</dt>
                            <dd>{@$achievementsStatistics['33096']}</dd>
                            <dt>+5</dt>
                            <dd>{@$achievementsStatistics['33097']}</dd>
                            <dt>+10</dt>
                            <dd>{@$achievementsStatistics['33098']}</dd>
                            <dt>+15</dt>
                            <dd>{@$achievementsStatistics['32028']}</dd>
                        </dl>
                    </nav>
                </section>
            </div>
        </aside>
    </div>
</div>

{include file='footer'}