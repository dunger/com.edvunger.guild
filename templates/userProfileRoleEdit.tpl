{include file='userMenuSidebar'}

{include file='header' __disableAds=true}

{include file='formError'}

{if $success|isset}
    <p class="success">{lang}wcf.global.success.edit{/lang}</p>
{/if}

<form method="post" action="{link application="guild" controller='UserRoleEdit'}{/link}">
    {foreach from=$memberList item=guild}
        <section class="section">
            <h2 class="sectionTitle">{$guild['guild']->getTitle()}</h2>

            {foreach from=$guild['objects'] item=member}
                <dl{if $errorField == $member->nameNormalize} class="formError"{/if}>
                    <dt><label for="memberRoles[{$member->nameNormalize}]">{$member->name} [{if $member->isMain}{lang}guild.acp.member.isMain.yes{/lang}{else}{lang}guild.acp.member.isMain.no{/lang}{/if}]</label></dt>
                    <dd>
                        <select id="memberRoles[{$member->nameNormalize}]" name="memberRoles[{$member->nameNormalize}]">
                            <option value="0"{if !$member->roleID} selected{/if}>{lang}wcf.global.noSelection{/lang}</option>
                            {foreach from=$member->getGuild()->getRoles() item=$role}
                                <option value="{$role->roleID}"{if $role->roleID == $member->roleID} selected{/if}>{lang}{$role->name}{/lang}</option>
                            {/foreach}
                        </select>
                        {if $errorField == $member->memberID}
                            <small class="innerError">
                                {if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
                                {if $errorType == 'invalid'}{lang}wcf.global.form.error.invalid{/lang}{/if}
                            </small>
                        {/if}
                    </dd>
                </dl>
            {/foreach}

        </section>
    {/foreach}

    <div class="formSubmit">
        <input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
        {@SECURITY_TOKEN_INPUT_TAG}
    </div>
</form>

<script data-relocate="true">
    $(function() {
        new WCF.Option.Handler();
    });
</script>

{include file='footer' __disableAds=true}
