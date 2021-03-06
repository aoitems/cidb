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
if (!INSIDE_CMS) {
    header("location: /");
    return;
}

// Collect query information.
$data['search'] = $_GET['search'];
$bot = $_GET['bot'];
$data['ql'] = GetQuality();    // Quality
$data['type'] = GetItemType();   // Item Type
$data['slots'] = GetSlots();    // Item Slots
$data['max'] = GetMaxResults();   // Maximum results to return


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
if ($outputversion == 2.0) {
    $sql = GenerateSqlQuery20($db, $data);
}

if ($_GET["debug"] > 0) {
    echo "<pre>" . $sql . "</pre><br /><br />";
}

$result = $db->query(FinalizeSqlString($sql));

if ($db->errno > 0) {
    $results = 0;
} else {
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
        } else {
            $lowid = $row['highid'];
            $highid = $row['lowid'];
            $lowql = $row['highql'];
            $highql = $row['lowql'];
            $highname = $row['lowname'];
            $lowname = $row['highname'];
        }

        if ($row['icon'] > 0) {
            $iconid = $row['icon'];
        } else {
            $iconid = 0;
        }

        if ($data['ql'] > 0) {
            if ((($highql - $lowql) / 2) > ($data['ql'] - $lowql)) {
                $name = $lowname;
            } else {
                $name = $highname;
            }
        } else {
            $name = $lowname;
        }

        /* TODO:
         * 	If item is at "max level" (i.e. highid is for ql 200, and item is ql 200), should match for only highid.
         *
         *
         *
         */
        // Get itemloc info
        global $CONFIG;
        $db->select_db($CONFIG['lootinfo_database']);
        $itemloc = $db->query("SELECT DISTINCT
		mt.description as Type,
		gm.name as Description
		
		FROM `itemMap` AS im
		LEFT JOIN `itemMapTypes` AS mt ON (mt.id=im.type)
		LEFT JOIN `itemGeneralMap` AS gm ON (gm.id=im.lookup)
		
		WHERE item_lowid='" . $lowid . "' OR item_highid='" . $highid . "'");

        $sources = array();
        while ($loot = $itemloc->fetch_assoc()) {
            $sources[] = $loot;
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

$outarray["Revision"] = $outputversion;
$outarray['Version'] = VERSION;
$outarray['Source'] = SOURCE;
$outarray['Server'] = SERVER;
$outarray['SearchString'] = $data['search'];
$outarray['SearchQuality'] = $data['ql'];
$outarray['MaxResults'] = $data['max'];
$outarray['Results'] = $rows;

if ($output == "json") {
    header('Content-Type: application/json');
    if ($output=="jsonp") {
      echo $_GET["callback"]."(".json_encode($outarray).");";
    }
    else {
      echo json_encode($outarray);
    }
    return;
}

if ($output == "xml") {
    /*   * * DISPLAY RESULTS ** */
    $smarty->assign('outarray', $outarray);
    header("Content-Type: text/xml");
    $smarty->display("2.0/items.xml.tpl");
}
?>