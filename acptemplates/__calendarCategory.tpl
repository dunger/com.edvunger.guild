{if !$guildList->objectIDs|empty}
    <dl{if $errorField == 'guildID'} class="formError"{/if}>
        <dt><label>{lang}guild.acp.category.guild{/lang}</label></dt>
        <dd>
            <select class="inputSuffix" name="guildID">
                <option value="0" {if $action == 'edit' && (!$category->additionalData['guildID']|isset || $category->additionalData['guildID'] == 0)} selected{/if}>{lang}wcf.global.noSelection{/lang}</option>
                {foreach from=$guildList item=guild}
                    <option value="{@$guild->guildID}"{if $action == 'edit' && $category->additionalData['guildID']|isset && $category->additionalData['guildID'] == $guild->guildID} selected{/if}>{@$guild->name}</option>
                {/foreach}
            </select>
        </dd>
    </dl>
{/if}