{php}header("Content-Type: text/plain");{/php}#Version={$version}
{if $outputversion>=1.2}#Server={$server}
{/if}
{if $outputversion>=1.2}#Revision={$outputversion}
{/if}
{if $outputversion>=1.2}#Search={$search}
{/if}
#Source={$source}
#QL={$ql}
#Max={$max}
#Results={$results_count}
#Fields=Name;LowID;HighID;LowQL;HighQL;IconID;Type;Slot;DefaultSlot
{foreach from=$results item=result}{$result.Name|escape:'html'};{$result.LowID};{$result.HighID};{$result.LowQL};{$result.HighQL};{$result.Icon};{$result.Type};{$result.Slot};{$result.DefaultSlot}
{/foreach}