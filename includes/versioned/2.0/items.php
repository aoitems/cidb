<?php
if (!INSIDE_CMS)
{
	header("location: /");
	return;
}

// Collect query information.
$data['search'] = $_GET['search'];
$bot = $_GET['bot'];
$data['ql'] = GetQuality(); 			// Quality
$data['type']=GetItemType();			// Item Type
$data['slots']=GetSlots();				// Item Slots
$data['max'] = GetMaxResults();			// Maximum results to return


$db=ConnectToDatabase($CONFIG);
if (CONNECTED === false) 
{
	die("Error: Couldn't connect to database");
}
// Log request
$db->query("INSERT INTO `log` (ip, bot, hits) VALUES ('".$db->real_escape_string($_SERVER['REMOTE_ADDR'])."', '".$db->real_escape_string($bot)."', 1) ON DUPLICATE KEY UPDATE hits = hits + 1");

// Make the right SQL query depending on output version
if ($outputversion == 2.0)	{ $sql = GenerateSqlQuery12($db, $data); }

if ($_GET["debug"]>0) 
{
	echo "<pre>".$sql."</pre><br /><br />";
}

$result = $db->query(FinalizeSqlString($sql));

if ($db->errno > 0) 
{
	$results = 0;
} 
else 
{
	$results = $result->num_rows;
}

$rows = array();

if ($results > 0) 
{
	while ($row = $result->fetch_assoc()) 
	{
		if ($row['lowql'] <= $row['highql']) {
			$lowid = $row['lowid'];
			$highid = $row['highid'];
			$lowql = $row['lowql'];
			$highql = $row['highql'];
			$highname = $row['highname'];
			$lowname = $row['lowname'];
		} 
		else 
		{
			$lowid = $row['highid'];
			$highid = $row['lowid'];
			$lowql = $row['highql'];
			$highql = $row['lowql'];
			$highname = $row['lowname'];
			$lowname = $row['highname'];
		}
		
		if ($row['icon'] > 0) 
		{
			$iconid = $row['icon'];
		} 
		else 
		{
			$iconid = 0;
		}
		
		if ($data['ql'] > 0) 
		{
			if ((($highql-$lowql)/2) > ($data['ql']-$lowql)) 
			{
				$name = $lowname;
			} 
			else 
			{
				$name = $highname;
			}
		} 
		else 
		{
			$name = $lowname;
		}
		
		/* TODO:
		*	If item is at "max level" (i.e. highid is for ql 200, and item is ql 200), should match for only highid.
		*
		*
		*
		*/
		// Get itemloc info
		global $CONFIG;
		$db->select_db($CONFIG['lootinfo_database']);
		$itemloc=$db->query("SELECT DISTINCT 
		mt.description as Type,
		gm.name as Description
		
		FROM `itemMap` AS im
		LEFT JOIN `itemMapTypes` AS mt ON (mt.id=im.type)
		LEFT JOIN `itemGeneralMap` AS gm ON (gm.id=im.lookup)
		
		WHERE item_lowid='".$lowid."' OR item_highid='".$highid."'");
		
		$sources=array();
		while ($loot=$itemloc->fetch_assoc())
		{
			$sources[]=$loot;
		}
		
		$rows[] = array(
			'LowID' => $lowid,
			'HighID' => $highid,
			'LowQL' => $lowql,
			'HighQL' => $highql,
			'Name' => $name,
			'Icon' => $iconid,
			'Type' => $row['itemtype'],
			'Slot' => $row['slot'],
			'DefaultSlot' => $row['defaultpos'],
			'Sources' => $sources,
		);
	}
}

//send_cache_headers(3600*24);

$outarray["Revision"]=$outputversion; 
$outarray['Version'] = VERSION;
$outarray['Source'] = SOURCE;
$outarray['Server'] = SERVER;
$outarray['SearchString'] = $data['search'];
$outarray['SearchQuality'] = $data['ql'];
$outarray['MaxResults'] = $data['max'];
$outarray['Results'] = $rows;

if ($output=="json") 
{
	header('Content-Type: application/json');
	echo json_encode($outarray);
	return;
}

if ($output=="xml")
{
	/*** DISPLAY RESULTS ***/
	$smarty->assign('outarray', $outarray);
	header("Content-Type: text/xml");
	$smarty->display("2.0/output.xml.tpl");
}
?>