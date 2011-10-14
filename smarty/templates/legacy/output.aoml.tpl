{if $results_count > 0}<font color={$color_header}>{$results_count}</font> <font color={$color_highlight}>Results :: </font><a href="text://<font color={$color_normal}><font color={$color_header}>Central Items Database</font>
<font color={$color_highlight}>Server:</font> {$server}
<font color={$color_highlight}>Database Version:</font> {$version} {$source}
<font color={$color_highlight}>Query:</font> {$search|escape:'html'}
<font color={$color_highlight}>Results:</font> {$results_count} / {$max}</font>

<font color={$color_header}>Results</font>
{foreach from=$results item=result}<font color={$color_highlight}>{$result.Name|escape:'html'}</font><font color={$color_normal}> [<a href='itemref://{$result.LowID}/{$result.HighID}/{$result.LowQL}'>QL {$result.LowQL}</a>]{if $ql != 0 && $ql > $result.LowQL} [<a href='itemref://{$result.LowID}/{$result.HighID}/{$ql}'>QL {$ql}</a>]{/if}{if $ql < $result.HighQL && $result.HighQL != $result.LowQL} [<a href='itemref://{$result.LowID}/{$result.HighID}/{$result.HighQL}'>QL {$result.HighQL}</a>]{/if}</font>
{if $result.Icon > 0 && $display_icon}<img src='rdb://{$result.Icon}'>
{/if}{/foreach}
">Click to View</a>{else}<font color={$color_highlight}>Your query returned 0 results{/if}