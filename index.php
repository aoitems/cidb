<?php
require_once('global.inc.php');

$bannedbots[]="bannedbot";
$bannedips[]="0.0.0.0";

/*** DEFAULT PAGE ***/
if (empty($_GET['output'])) 
{
	define("INSIDE_CMS",true);
	require_once("default.php");
	$smarty->display('index.tpl');
	exit();
}

/*** PREPARE SEARCH ***/
// Output type
$output = strip_tags(basename(strtolower($_GET['output'])));
$template = 'output.'.$output.'.tpl';
if (($output != "json" && !$smarty->templateExists($template))) 
{
	error('Invalid "Output" value');
}

// Per-template configuration
if ($output=="aoml") 
{
	$smarty->assign('color_header', ((isset($_GET['color_header']) && strlen($_GET['color_header']) == 6) ? '#'.$_GET['color_header'] : '#FFFFFF'));
	$smarty->assign('color_highlight', ((isset($_GET['color_highlight']) && strlen($_GET['color_highlight']) == 6) ? '#'.$_GET['color_highlight'] : '#79CBE6'));
	$smarty->assign('color_normal', ((isset($_GET['color_normal']) && strlen($_GET['color_normal']) == 6) ? '#'.$_GET['color_normal'] : '#CCCCCC'));
	$smarty->assign('display_icon', ((isset($_GET['display_icon']) && $_GET['display_icon'] == 'false') ? false : true));
}


// Required input
if (empty($_GET['search'])) 
{
	error('Value "Search" not defined!');
}
if (empty($_GET['bot'])) 
{
	error('Value "Bot" not defined!');
}
$search = $_GET['search'];
$bot = $_GET['bot'];

// Quality
$ql = 0;
if (!empty($_GET['ql'])) 
{
	if (!is_numeric($_GET['ql'])) 
	{
		error('Invalid "Ql" value');
	}
	if ($_GET['ql'] >= 0 && $_GET['ql'] <= 999) 
	{
		$ql = (int)$_GET['ql'];
	}
}

// Output version
$outputversion = 1.1;
if (!empty($_GET['version'])) 
{
	$outputversion = round($_GET['version'],1);
	if ($_GET['version']<1.1 || $_GET['version']>1.2) 
	{
		error('Unsupported version. I support versions: 1.1, 1.2');
	}
}

// Item Type
$type=false;
if (!empty($_GET['type'])) 
{
	$type=$_GET['type'];
}

// Slot
$slots=array();
if (!empty($_GET['slot'])) 
{
	$slots = explode(",", $_GET["slot"]);
}

// Maximum results
$max = $CONFIG['default_max'];
if (!empty($_GET['max'])) 
{
	if (!is_numeric($_GET['max'])) 
	{
		error('Invalid "Max" value');
	}
	if ($_GET['max'] > $CONFIG['real_max']) 
	{
		$max = $CONFIG['real_max'];
	} else 
	{
		$max = (int)$_GET['max'];
	}
}
$db=connect_to_db($CONFIG);


if (CONNECTED === false) 
{
	die("Error: Couldn't connect to database");
}
// Log request
$sql = "INSERT INTO `log` (ip, bot, hits) VALUES ('".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."', '".mysql_real_escape_string($bot)."', 1) ON DUPLICATE KEY UPDATE hits = hits + 1";
mysql_query($sql);
//echo mysql_error();

/*** SEARCH DATABASE ***/
$sql =	"SELECT t1.lowid, t1.highid, t2.ql as lowql, t3.ql as highql, t2.name as lowname, t3.name as highname, t2.icon, t2.itemtype, t2.slot, t2.defaultpos ".
		"FROM item_relations t1 LEFT JOIN (items t2, items t3) ON (t1.lowid = t2.aoid AND t1.highid = t3.aoid) ".
		"WHERE t2.name LIKE '%".mysql_real_escape_string(str_replace(' ', '%', $search), $db)."%' ";
if ($ql > 0) 
{
	$sql .= ' AND ((t2.ql <= '.$ql.' AND t3.ql >= '.$ql.') OR (t2.ql >= '.$ql.' AND t3.ql <= '.$ql.')) ';
}
if ($type !== false) 
{
	$sql .= " AND t2.itemtype='".mysql_real_escape_string($type, $db)."' ";	
}
else 
{
	// Exclude implants by default
	$sql.=" AND t2.itemtype!='implant' ";
}

if (is_array($slots) && count($slots)>0) 
{
	$sql.=" AND (";
	$set=false;
	foreach ($slots as $slot) 
	{
		if ($set===true)
		{
			$sql.=" OR";
		}
		$sql.= " t2.slot LIKE '%".mysql_real_escape_string($slot, $db)."%' ";
		$set=true;
	}
	$sql.=") ";
}

$sql .=	"ORDER BY t2.name ASC, t2.ql DESC, t3.ql DESC LIMIT 0, ".$max;

if ($_GET["debug"]>0) 
{
	echo "<pre>".$sql."</pre><br /><br />";
}
$result = mysql_query($sql);

if (mysql_errno() > 0) 
{
	$results = 0;
} 
else 
{
	$results = mysql_num_rows($result);
}
$rows = array();
if ($results > 0) 
{
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
	{
		if ($row['lowql'] < $row['highql']) {
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
		if ($ql > 0) 
		{
			if ((($highql-$lowql)/2) > ($ql-$lowql)) 
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
		
		$rows[] = array(
			'LowID' => $lowid,
			'HighID' => $highid,
			'LowQL' => $lowql,
			'HighQL' => $highql,
			'Name' => $name,
			'Icon' => $iconid,
			'Type' => $row['itemtype'],
			'Slot' => $row['slot'],
			'DefaultSlot' => $row['defaultpos']
		);
	}
}

//send_cache_headers(3600*24);

if ($output=="json") 
{
	if ($outputversion>=1.2) 
	{ 
		$outarray["revision"]=$outputversion; 
	}
	$outarray['version'] = VERSION;
	$outarray['source'] = SOURCE;
	$outarray['server'] = SERVER;
	$outarray['search'] = $search;
	$outarray['ql'] = $ql;
	$outarray['max'] = $max;
	$outarray['results_count'] = $results;
	$outarray['results'] = $rows;
	header('Content-Type: application/json');
	echo json_encode($outarray);
	return;
}

/*** DISPLAY RESULTS ***/
$smarty->assign('version', VERSION);
$smarty->assign('source', SOURCE);
$smarty->assign('server', SERVER);
$smarty->assign('search', $search);
$smarty->assign('ql', $ql);
$smarty->assign('max', $max);
$smarty->assign('results_count', $results);
$smarty->assign('results', $rows);
$smarty->assign('outputversion', $outputversion);
$smarty->display($template);
?>