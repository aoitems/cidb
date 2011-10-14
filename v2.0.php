<?php
// Output type
$output = strip_tags(basename(strtolower($_GET['output'])));
if ($output!="xml" && $output!="json")
{
	Error('Invalid "Output" value');
}

require_once(GetSource20());
?>