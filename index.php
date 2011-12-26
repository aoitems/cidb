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
define("INSIDE_CMS",true);
require_once('includes/global.inc.php');

$bannedbots[]="bannedbot";
$bannedips[]="0.0.0.0";

/*** DEFAULT PAGE ***/
if (empty($_GET['output'])) 
{
	require_once("includes/default.php");
	$smarty->assign('header', $CONFIG['header']);
	$smarty->assign('title', $CONFIG['title']);
	$smarty->display('index.tpl');
	exit();
}

/*** DEFINE OUTPUT TEMPLATE ***/
$outputversion = GetOutputVersion(); 	// Output version
/*** INCLUDE RIGHT FILE FOR APPROPIATE CIDB VERSION ***/
$workfile="";
switch($outputversion) 
{
	case 1.1:
	case 1.2:
		$workfile="./includes/versioned/v1.1-1.2.php";
		break;
	case 2.0:
		$workfile="./includes/versioned/v2.0.php";
		break;
	default:
		Error('Unsupported output version!');
		break;
}

require_once($workfile);
?>
