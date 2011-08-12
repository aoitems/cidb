<?php
// Retrieves the QL of the request
function GetQuality() 
{
	$ql=0;
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
	return $ql;
}

function GetOutputVersion() 
{
	$outputversion = 1.1;
	if (!empty($_GET['version']))
	{
		$outputversion = round($_GET['version'],1);
		if ($_GET['version']<1.1 || $_GET['version']>1.2)
		{
			error('Unsupported version. I support versions: 1.1, 1.2');
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
			error('Invalid "Max" value');
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
?>