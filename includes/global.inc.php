<?php
/*
BotSharp CIDB is made available under the MIT licence.
Copyright (c) 2011 Demoder <demoder@demoder.me>

Based on the original work by Remco van Oosterhout on VhaBot CIDB (http://www.vhabot.net/downloads.php)

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

if (!INSIDE_CMS)
{
	header("location: /");
	return;
}

require_once('config.php');
require_once('includes/functions.php');

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
		$q = $db->query("SELECT * FROM metadata ORDER BY time DESC LIMIT 1");
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