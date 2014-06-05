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
function GetQuality() {
  $ql = 0;
  if (!empty($_GET['ql'])) {
    if (!is_numeric($_GET['ql'])) {
      Error('Invalid "Ql" value');
    }
    if ($_GET['ql'] >= 0 && $_GET['ql'] <= 999) {
      $ql = (int) $_GET['ql'];
    }
  }
  return $ql;
}

function GetOutputVersion() {
  $outputversion = 1.1;
  if (!empty($_GET['version'])) {
    $outputversion = round($_GET['version'], 1);
    switch ($outputversion) {
      case 1.1:
      case 1.2:
      case 2.0:
        break;
      default:
        Error('Unsupported version. I support versions: 1.1, 1.2, 2.0');
        break;
    }
  }
  return $outputversion;
}

function GetItemType() {
  $type = false;
  if (!empty($_GET['type'])) {
    $type = $_GET['type'];
  }
  return $type;
}

function GetSlots() {
  $slots = array();
  if (!empty($_GET['slot'])) {
    $slots = explode(",", $_GET["slot"]);
  }
  return $slots;
}

function GetSource20() {
  switch ($_GET["source"]) {
    case "items": return "./includes/versioned/2.0/items.php";
    //case "monsters": return "monsters_2.0.php";
    //case "shop": return "shop_2.0.php";
    case "itemid": return "./includes/versioned/2.0/itemid.php";
    default:
      Error("Invalid source specified");
  }
}

function GetMaxResults() {
  $max = DEFAULT_MAX;
  if (!empty($_GET['max'])) {
    if (!is_numeric($_GET['max'])) {
      Error('Invalid "Max" value');
    }
    if ($_GET['max'] > REAL_MAX) {
      $max = REAL_MAX;
    }
    else {
      $max = (int) $_GET['max'];
    }
  }
  return $max;
}

function SendCacheHeaders($seconds_to_cache, $public = true) {
  $ts = gmdate("D, d M Y H:i:s", (time() + $seconds_to_cache)) . " GMT";
  header("Expires: $ts");
  header("Pragma: cache");
  if ($public)
    $cc = "public";
  else
    $cc = "private";
  header("Cache-Control: $cc, maxage=$seconds_to_cache");
}

// Database Connection
function ConnectToDatabase($config) {
  $db = new mysqli($config["host"], $config["user"], $config["password"]);
  if ($db->connect_errno > 0) {
    define('CONNECTED', false);
  }
  else {
    $db->select_db($config["database"]);
    if (mysql_errno() > 0) {
      define('CONNECTED', false);
    }
    else {
      define('CONNECTED', true);
    }
  }
  // Fetch metadata
  $xcache_metadata_name = md5($config['header'] . "metadata");
  if (!xcache_isset($xcache_metadata_name)) {
    $q = $db->query("SELECT * FROM metadata ORDER BY time DESC LIMIT 1");
    $metadata = $q->fetch_assoc();
    xcache_set($xcache_metadata_name, gzdeflate(serialize($metadata), 3), 300);
  }
  else {
    $metadata = unserialize(gzinflate(xcache_get($xcache_metadata_name)));
  }
  define('VERSION', $metadata["version"]);
  define('SOURCE', $metadata["source"]);
  define('UPDATED', $metadata["time"]);
  define('ITEMS', $metadata["items"]);
  define('RELATIONS', $metadata["relations"]);
  return $db;
}

// Error Output
function Error($message) {
  global $smarty;
  $smarty->assign('message', $message);
  $smarty->display('error.tpl');
  exit();
}

// Generate SQL queries

function GenerateSqlQueryBase($db, $data) {
  $words = explode(" ", $data["search"]);
  $likestring = array();
  $notlikestring = array();
  $matchstring = array();
  foreach ($words as $word) {
    if (substr($word, 0, 1) != "-") {
      $likestring[] = $word;
      $matchstring[] = "+" . $word;
    }
    else {
      $notlikestring[] = substr($word, 1);
    }
  }
  $likestring = implode("%", $likestring);
  $matchstring = implode(" ", $matchstring);


  // Filter by name
  $sql["select"] = " SELECT t1.lowid, t1.highid, t2.ql as lowql, t3.ql as highql, t2.name as lowname, t3.name as highname, t2.icon, t2.itemtype, t2.slot, t2.defaultpos, " .
          "MATCH(t2.name) AGAINST ('" . $db->real_escape_string($matchstring) . "') as Relevance ";
  $sql["from"] = " FROM item_relations t1 LEFT JOIN (items t2, items t3) ON (t1.lowid = t2.aoid AND t1.highid = t3.aoid) ";
  $sql["where"] = " WHERE ((t2.name LIKE '%" . $db->real_escape_string($likestring) . "%' ";
  foreach ($notlikestring as $notlike) {
    $sql["where"].=" AND t2.name NOT LIKE '%" . $db->real_escape_string($notlike) . "%' ";
  }
  $sql["where"].=") ";
  $sql["where"].=" OR (t3.name LIKE '%" . $db->real_escape_string($likestring) . "%' ";
  foreach ($notlikestring as $notlike) {
    $sql["where"].=" AND t3.name NOT LIKE '%" . $db->real_escape_string($notlike) . "%' ";
  }
  $sql["where"].=")) ";

  // Filter by QL
  if ($data['ql'] > 0) {
    $sql["where"] .= " AND ((t2.ql <= '" . $db->real_escape_string($data['ql']) . "' AND t3.ql >= '" . $db->real_escape_string($data['ql']) . "') OR (t2.ql >= '" . $db->real_escape_string($data['ql']) . "' AND t3.ql <= '" . $db->real_escape_string($data['ql']) . "')) ";
  }

  // Check slots
  if (is_array($data['slots']) && count($data['slots']) > 0) {
    $sql["where"].=" AND (";
    $set = false;
    foreach ($data['slots'] as $slot) {
      if ($set === true) {
        $sql["where"].=" OR";
      }
      $sql["where"].= " t2.slot LIKE '%" . $db->real_escape_string($slot) . "%' ";
      $set = true;
    }
    $sql["where"].=") ";
  }
  // Return data
  return $sql;
}

// Search criteria specific for output version 1.1
function GenerateSqlQuery11($db, $data) {
  $sql = GenerateSqlQueryBase($db, $data);

  if ($data['type'] !== false) {
    $sql["where"] .= " AND t2.itemtype='" . $db->real_escape_string($data['type']) . "' ";
  }
  else if (stristr($data['search'], "imp") === false) {
    // Exclude implants by default, but only if version is 1.1 and search string doesn't want imps
    $sql["where"].=" AND (t2.itemtype!='implant' OR (t2.itemtype='implant' && t2.name NOT LIKE '%implant%')) ";
    $sql["where"].= " AND t2.`itemtype` != 'Nano' ";
  }
  $sql["orderby"] = "ORDER BY Relevance DESC, t2.name ASC, t2.ql DESC, t3.ql DESC LIMIT 0, " . $db->real_escape_string($data['max']);
  return $sql;
}

// Search criteria specific for output version 1.2
function GenerateSqlQuery12($db, $data) {
  $sql = GenerateSqlQueryBase($db, $data);

  if ($data['type'] !== false) {
    $sql["where"] .= " AND t2.itemtype='" . $db->real_escape_string($data['type']) . "' ";
  }
  else {
    // Exclude implants by default, but only if version is 1.1 and search string doesn't want imps
    $sql["where"].=" AND (t2.itemtype!='implant' OR (t2.itemtype='implant' && t2.name NOT LIKE '%implant%')) ";
    $sql["where"].= " AND t2.`itemtype` != 'Nano' ";
  }

  $sql["orderby"] = "ORDER BY Relevance DESC, t2.name ASC, t2.ql DESC, t3.ql DESC LIMIT 0, " . $db->real_escape_string($data['max']);
  return $sql;
}

function GenerateSqlQuery20($db,$data){
  $sql = GenerateSqlQueryBase($db, $data);
  if ($data['type'] !== false) {
    $sql["where"] .= " AND t2.itemtype='" . $db->real_escape_string($data['type']) . "' ";
  }
  else {
    // Exclude implants by default, but only if version is 1.1 and search string doesn't want imps
    $sql["where"].=" AND (t2.itemtype!='implant' OR (t2.itemtype='implant' && t2.name NOT LIKE '%implant%')) ";
  }

  $sql["orderby"] = "ORDER BY Relevance DESC, t2.name ASC, t2.ql DESC, t3.ql DESC LIMIT 0, " . $db->real_escape_string($data['max']);
  return $sql;
}

function FinalizeSqlString($sql) {
  return $sql["select"] . $sql["from"] . $sql["where"] . $sql["orderby"];
}

?>
