{hascontent}
<div class="boxContent progressBox">
    {content}
        {foreach from=$raidProgress item=progress}
            <h2 class="boxTitle">{$progress['guild']->name}</h2>
            {foreach from=$progress['data'] item=item}
                <div class="progress" data-label="{$item['title']} {$item['kills']}/{$item['encounters']}"><span class="value" style="width:{$item['percent']}%;"></span></div>
                <div style="height: 10px">&nbsp;</div>
            {/foreach}
        {/foreach}
    {/content}
</div>
{/hascontent}