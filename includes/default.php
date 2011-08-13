<?php
/*
BotSharp CIDB is made available under the MIT licence.
Copyright (c) 2011 Demoder <demoder@demoder.me>

Based on the original work by Remco van Oosterhout on VhaBot CIDB

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
$db=ConnectToDatabase($CONFIG);
if (CONNECTED === false)
{
	die("Error: Couldn't connect to database");
}
$smarty->assign("maxresults", $CONFIG['real_max']);
$smarty->assign("defaultresults", $CONFIG['default_max']);
$smarty->assign("updated", gmdate("Y-m-d H:i", UPDATED)." GMT");
$smarty->assign("version", VERSION);
$smarty->assign("source", SOURCE);
$smarty->assign("items", number_format(ITEMS,0,".",","));
$smarty->assign("relations", number_format(RELATIONS,0,".",","));


/*	Find top bots, by requests */
$sql="select sum(hits) as hits from log WHERE bot!='example'";
$totalhits = $db->query($sql);

$totalhits = $totalhits->fetch_assoc();
$onepercent = $totalhits["hits"]/100;

if (! xcache_isset("topbots")) 
{
	$sql="select bot as botname,
	SUM(hits) as totalhits
	from log WHERE bot!='example' group by botname order by totalhits desc limit 0,5";
	$result=$db->query($sql);
	while($row = $result->fetch_assoc()) 
	{
		$topbots[$row["botname"]]=round($row["totalhits"]/$onepercent,2);
	}
	xcache_set("topbots", gzdeflate(serialize($topbots), 3), 600);
}
else 
{
	$topbots = unserialize(gzinflate(xcache_get("topbots")));
}
$smarty->assign("topbots_byrequests", $topbots);
?>
