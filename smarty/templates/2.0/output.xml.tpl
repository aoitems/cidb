<?xml version="1.0" encoding="UTF-8"?>

<items server="{$outarray["Server"]|escape:'html'}" revision="{$outarray["Revision"]|escape:'html'}" version="{$outarray["Version"]|escape:'html'}" source="{$outarray["Source"]|escape:'html'}" searchquality="{$outarray["SearchQuality"]|escape:'html'}" searchstring="{$outarray["SearchString"]|escape:'html'}" maxresults="{$outarray["MaxResults"]|escape:'html'}">
{foreach from=$outarray["Results"] item=result}
	<item name="{$result.Name|escape:'html'}" lowid="{$result.LowID|escape:'html'}" highid="{$result.HighID|escape:'html'}" lowql="{$result.LowQL|escape:'html'}" highql="{$result.HighQL|escape:'html'}" icon="{$result.Icon|escape:'html'}" type="{$result.Type|escape:'html'}" slot="{$result.Slot|escape:'html'}" defaultslot="{$result.DefaultSlot|escape:'html'}" >
	{foreach from=$result["Sources"] item=source}
	<source type="{$source["Type"]|escape:html}" description="{$source["Description"]|escape:html}" />
	{/foreach}
</item>
{/foreach}
</items>