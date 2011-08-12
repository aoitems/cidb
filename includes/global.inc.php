<?php
if (!INSIDE_CMS)
{
	header("location: /");
	return;
}

require_once('config.php');

define('REAL_MAX', $CONFIG['real_max']);
define('DEFAULT_MAX', $CONFIG['default_max']);

// Version Information
define('SERVER', $CONFIG['server']);

// Database Connection
function connect_to_db($config)
{
	$db = new mysqli($config["host"], $config["user"], $config["password"]);
	if ($db->connect_errno > 0)
	{
		define('CONNECTED', false);
	}
	else
	{
		$db->select_db($config["database"]);
		if (mysql_errno() > 0)
		{
			define('CONNECTED', false);
		}
		else
		{
			define('CONNECTED', true);
		}
	}
	// Fetch metadata
	if (!xcache_isset("metadata"))
	{
		$q = $sql->query("SELECT * FROM metadata ORDER BY time DESC LIMIT 1");
		$metadata = $q->fetch_assoc();
		xcache_set("metadata", gzdeflate(serialize($metadata), 3), 300);
	}
	else
	{
		$metadata = unserialize(gzinflate(xcache_get("metadata")));
	}
	define('VERSION', $metadata["version"]);
	define('SOURCE', $metadata["source"]);
	define('UPDATED', $metadata["time"]);
	define('ITEMS', $metadata["items"]);
	define('RELATIONS', $metadata["relations"]);
	return $db;
}

// Smarty Configuration
require_once($CONFIG['smarty']);
$smarty = new Smarty();

$smarty->template_dir = 'smarty/templates';
$smarty->compile_dir = 'smarty/templates_c';
$smarty->cache_dir = 'smarty/cache';
$smarty->config_dir = 'smarty/configs';

// Disable cache for now
$smarty->clearAllCache(0);
$smarty->caching = 0;
$smarty->compileCheck = true;
$smarty->forceCompile = true;
$smarty->allow_php_tag=true;


// Error Output
function error($message)
{
	global $smarty;
	$smarty->assign('message', $message);
	$smarty->display('error.tpl');
	exit();
}

function send_cache_headers($seconds_to_cache, $public=true)
{
	$ts = gmdate("D, d M Y H:i:s", (time() + $seconds_to_cache)) . " GMT";
	header("Expires: $ts");
	header("Pragma: cache");
	if ($public)
	$cc="public";
	else
	$cc="private";
	header("Cache-Control: $cc, maxage=$seconds_to_cache");
}
?>