<?php

use myClss\Db;
set_time_limit(0);
ini_set('memory_limit', -1);

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/funcs.php";
$db_config = require __DIR__ . "/config/db.php";
$client = new \GuzzleHttp\Client();
$document = new \DiDom\Document();
$prefix = "https://tender.rusal.ru";
$filterId = "bef4c544-ba45-49b9-8e91-85d9483ff2f6";
$apiUrl = $prefix . "/Tenders/Load";
$db = Db::getInstance()->connection($db_config);
echo "Start parsing...\n";
echo "Clearing DB...\n";
clearDB($db);
echo "Clearing DB complete\n";
echo "Getting Tenders data...\n";
$tendersCount = getTendersCount($apiUrl, $client);
if (!$tendersCount)
{
    echo "Can't get data from site";
    die();
}
$tenders = getTenders($apiUrl, $client, $filterId, $tendersCount);
echo "Getting Tenders data complete\n";
$tendersData = [];
$tendersDocumentsData = [];

foreach ($tenders as $key => $tender) {
    echo "Parsing Tender ". ($key + 1) . " of {$tendersCount}\n";
    $tendersData[$key]['TenderNumber'] = $tender['TenderNumber'];
    $tendersData[$key]['OrganizerName'] = $tender['OrganizerName'];
    $tendersData[$key]['TenderViewUrl'] = $prefix . $tender['TenderViewUrl'];
    $tendersData[$key]['RequestReceivingBeginDate'] = $tender['RequestReceivingBeginDate'];
    $tendersDocumentsData[$key] = getDocumentDataFromTender($tendersData[$key]['TenderViewUrl'], $client, $document, $prefix, $tendersData[$key]['TenderNumber']);
    saveTenderInDB($db, $tendersData[$key], $tendersDocumentsData[$key]);
    sleep(rand(1, 3));
}
echo "Parsing end\n";




