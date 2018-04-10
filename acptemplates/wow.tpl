{include file='header' pageTitle='guild.acp.game.wow.settings'}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}guild.acp.game.wow.settings{/lang}</h1>
    </div>
</header>

<div class="row">
    <div class="col-md-3">
        <section class="box" style="text-align: center;">
            <h2 class="boxTitle">
                <a href="{link application='guild' controller='WowInstanceList'}{/link}">
                    <span class="icon icon128 fa-fort-awesome" style="display: block; margin-bottom: 10px; width: auto;"></span>
                    <span>{lang}guild.acp.game.wow.instances{/lang}</span>
                </a>
            </h2>

            <div class="boxContent"></div>
        </section>
    </div>
    <div class="col-md-3">
        <section class="box" style="text-align: center;">
            <h2 class="boxTitle">
                <a href="{link application='guild' controller='WowEncounterList'}{/link}">
                    <span class="icon icon128 fa-bug" style="display: block; margin-bottom: 10px; width: auto;"></span>
                    <span>{lang}guild.acp.game.wow.encounter{/lang}</span>
                </a>
            </h2>

            <div class="boxContent"></div>
        </section>
    </div>
<!--
    <div class="col-md-3">
        <section class="box" style="text-align: center;">
            <h2 class="boxTitle">
                <a href="{link application='guild' controller='WowStatistic'}{/link}">
                    <span style="display: block; margin-bottom: 10px; width: auto;"><img src="{$__wcf->getPath('guild')}/images/wow/instance.png" alt=""  class="" /></span>
                    <span>{lang}guild.acp.game.wow.statistic{/lang}</span>
                </a>
            </h2>

            <div class="boxContent"></div>
        </section>
    </div>
-->
</div>

{include file='footer'}