<?php
$db=connect_to_db($CONFIG);
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
$totalhits = $totalhists->fetch_assoc();
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