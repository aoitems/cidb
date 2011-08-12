{php}header("Content-Type: text/html");{/php}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" encoding="utf-8"> 
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{$search|escape:'html'} - Bot# CIDB</title>
	</head>
	
	<body>
		<ul>
			<li>Server: {$server|escape:'html'}</li>
			<li>Search: {$outputversion|escape:'html'} (QL {$ql|escape:'html'})</li>
			<li>Source: {$version|escape:'html'} ({$source|escape:'html'})</li>
			<li>Results: {$results_count|escape:'html'} (max: {$max|escape:'html'})</li>
		</ul>
		<br /><br />
		{foreach from=$results item=result}
			<a href="http://www.xyphos.com/ao/aodb.php?id={$result.HighID|escape:'html'}{if $ql>0}&amp;ql={$ql|escape:'html'}{/if}" target="_blank">{$result.Name|escape:'html'}</a><br />
		{/foreach}
	</body>
</html>