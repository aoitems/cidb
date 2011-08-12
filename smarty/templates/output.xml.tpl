{php}header("Content-Type: text/xml");{/php}<?xml version="1.0" encoding="UTF-8"?>
<items>
{if $outputversion >= 1.2}<server>{$server|escape:'html'}</server>
{/if}
{if $outputversion >= 1.2}<revision>{$outputversion|escape:'html'}</revision>
{/if}
{if $outputversion >= 1.2}<search>{$search|escape:'html'}</search>
{/if}
<version>{$version|escape:'html'}</version>
<source>{$source|escape:'html'}</source>
<ql>{$ql|escape:'html'}</ql>
<max>{$max|escape:'html'}</max>
<results>{$results_count|escape:'html'}</results>
{foreach from=$results item=result}<item name="{$result.Name|escape:'html'}" lowid="{$result.LowID|escape:'html'}" highid="{$result.HighID|escape:'html'}" lowql="{$result.LowQL|escape:'html'}" highql="{$result.HighQL|escape:'html'}" icon="{$result.Icon|escape:'html'}" type="{$result.Type|escape:'html'}" slot="{$result.Slot|escape:'html'}" defaultslot="{$result.DefaultSlot|escape:'html'}" />
{/foreach}
</items>