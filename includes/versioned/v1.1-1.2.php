<?php

if (!INSIDE_CMS) {
  header("location: /");
  return;
}

// Output type
$output = strip_tags(basename(strtolower($_GET['output'])));
$template = 'legacy/output.' . $output . '.tpl';
if (($output != "json" && !$smarty->templateExists($template))) {
  Error('Invalid "Output" value');
}

// Per-template configuration
if ($output == "aoml") {
  $smarty->assign('color_header', ((isset($_GET['color_header']) && strlen($_GET['color_header']) == 6) ? '#' . $_GET['color_header'] : '#FFFFFF'));
  $smarty->assign('color_highlight', ((isset($_GET['color_highlight']) && strlen($_GET['color_highlight']) == 6) ? '#' . $_GET['color_highlight'] : '#79CBE6'));
  $smarty->assign('color_normal', ((isset($_GET['color_normal']) && strlen($_GET['color_normal']) == 6) ? '#' . $_GET['color_normal'] : '#CCCCCC'));
  $smarty->assign('display_icon', ((isset($_GET['display_icon']) && $_GET['display_icon'] == 'false') ? false : true));
}

// Required input
if (empty($_GET['search'])) {
  Error('Value "Search" not defined!');
}
if (empty($_GET['bot'])) {
  Error('Value "Bot" not defined!');
}
$data['search'] = $_GET['search'];
$bot = $_GET['bot'];

$data['ql'] = GetQuality();    // Quality
$data['type'] = GetItemType();   // Item Type
$data['slots'] = GetSlots();    // Item Slots
$data['max'] = GetMaxResults();   // Maximum results to return
// Version check
if (($output == "json" || $output == 'html') && $outputversion < 1.2) {
  Error($output . " is only available for version 1.2 and later");
}

$db = ConnectToDatabase($CONFIG);
if (CONNECTED === false) {
  die("Error: Couldn't connect to database");
}
// Log request
$db->query("INSERT INTO `log` (date,ip, bot, hits) VALUES ('"
	. $db->real_escape_string(gmdate("Y-m-d")) 
	. "', '" 
	. $db->real_escape_string($_SERVER['REMOTE_ADDR']) 
	. "', '" 
	. $db->real_escape_string($bot) 
	. "', 1) ON DUPLICATE KEY UPDATE hits = hits + 1");


// Make the right SQL query depending on output version
if ($outputversion == 1.1) {
  $sql = GenerateSqlQuery11($db, $data);
}
if ($outputversion == 1.2) {
  $sql = GenerateSqlQuery12($db, $data);
}

if ($_GET["debug"] > 0) {
  echo "<pre>" . $sql . "</pre><br /><br />";
}

$result = $db->query(FinalizeSqlString($sql));

if ($db->errno > 0) {
  $results = 0;
}
else {
  $results = $result->num_rows;
}

$rows = array();

if ($results > 0) {
  while ($row = $result->fetch_assoc()) {
    if ($row['lowql'] <= $row['highql']) {
      $lowid = $row['lowid'];
      $highid = $row['highid'];
      $lowql = $row['lowql'];
      $highql = $row['highql'];
      $highname = $row['highname'];
      $lowname = $row['lowname'];
    }
    else {
      $lowid = $row['highid'];
      $highid = $row['lowid'];
      $lowql = $row['highql'];
      $highql = $row['lowql'];
      $highname = $row['lowname'];
      $lowname = $row['highname'];
    }

    if ($row['icon'] > 0) {
      $iconid = $row['icon'];
    }
    else {
      $iconid = 0;
    }

    if ($data['ql'] > 0) {
      if ((($highql - $lowql) / 2) > ($data['ql'] - $lowql)) {
        $name = $lowname;
      }
      else {
        $name = $highname;
      }
    }
    else {
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
        'DefaultSlot' => $row['defaultpos'],
    );
  }
}

//send_cache_headers(3600*24);

if ($output == "json") {
  $outarray["revision"] = $outputversion;
  $outarray['version'] = VERSION;
  $outarray['source'] = SOURCE;
  $outarray['server'] = SERVER;
  $outarray['search'] = $data['search'];
  $outarray['ql'] = $data['ql'];
  $outarray['max'] = $data['max'];
  $outarray['results_count'] = $results;
  $outarray['results'] = $rows;
  header('Content-Type: application/json');
  echo json_encode($outarray);
  return;
}

/* * * DISPLAY RESULTS ** */
$smarty->assign('version', VERSION);
$smarty->assign('source', SOURCE);
$smarty->assign('server', SERVER);
$smarty->assign('search', $data['search']);
$smarty->assign('ql', $data['ql']);
$smarty->assign('max', $data['max']);
$smarty->assign('results_count', $results);
$smarty->assign('results', $rows);
$smarty->assign('outputversion', $outputversion);
$smarty->display($template);
?>