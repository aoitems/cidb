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

// Retrieves the QL of the request
function GetQuality() 
{
	$ql=0;
	if (!empty($_GET['ql']))
	{
		if (!is_numeric($_GET['ql']))
		{
			Error('Invalid "Ql" value');
		}
		if ($_GET['ql'] >= 0 && $_GET['ql'] <= 999)
		{
			$ql = (int)$_GET['ql'];
		}
	}
	return $ql;
}

function GetOutputVersion() 
{
	$outputversion = 1.1;
	if (!empty($_GET['version']))
	{
		$outputversion = round($_GET['version'],1);
		switch ($outputversion)
		{
			case 1.1:
			case 1.2:
				break;
			default:
				Error('Unsupported version. I support versions: 1.1, 1.2');
				break;
		}
	}
	return $outputversion;
}

function GetItemType() 
{
	$type = false;
	if (!empty($_GET['type']))
	{
		$type=$_GET['type'];
	}
	return $type;
}

function GetSlots() 
{
	$slots = array();
	if (!empty($_GET['slot']))
	{
		$slots = explode(",", $_GET["slot"]);
	}
	return $slots;
}

function GetMaxResults()
{
	$max = DEFAULT_MAX;
	if (!empty($_GET['max']))
	{
		if (!is_numeric($_GET['max']))
		{
			Error('Invalid "Max" value');
		}
		if ($_GET['max'] > REAL_MAX)
		{
			$max = REAL_MAX;
		} 
		else
		{
			$max = (int)$_GET['max'];
		}
	}
	return $max;
}

function SendCacheHeaders($seconds_to_cache, $public=true)
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

// Database Connection
function ConnectToDatabase($config)
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

// Error Output
function Error($message)
{
	global $smarty;
	$smarty->assign('message', $message);
	$smarty->display('error.tpl');
	exit();
}
?>
