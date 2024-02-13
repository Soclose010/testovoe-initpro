<?php


function getTendersCount(string $url, \GuzzleHttp\Client $client): int|bool
{
    $response = $client->post($url, [
            'form_params' => [
                'ClassifiersFieldData.SiteSectionType' => 'bef4c544-ba45-49b9-8e91-85d9483ff2f6',
            ],
        ]
    );
    $data = json_decode($response->getBody()->getContents(), true);
    if (empty($data))
    {
        return false;
    }
    return $data['Paging']['Total'];
}

function getTenders(string $url, \GuzzleHttp\Client $client, string $filterId, int $count): bool|array
{
    $response = $client->post($url, [
            'form_params' => [
                'ClassifiersFieldData.SiteSectionType' => $filterId,
                'offset' => 0,
                'limit' => $count,
            ],
        ]
    );
    $data = json_decode($response->getBody()->getContents(), true);
    if (empty($data))
    {
        return false;
    }
    return $data['Rows'];
}

function getDocumentDataFromTender(string $tenderUrl, \GuzzleHttp\Client $client, \DiDom\Document $document, string $prefix, string $tenderNumber): array
{
    $documentsData = [];
    $response = $client->post($tenderUrl);
    $document->loadHtml($response->getBody()->getContents());
    $aDocumentTags = $document->find("a.file-download-link");
    foreach ($aDocumentTags as $key => $aDocumentTag) {
        $documentsData[$key]['Tender_TenderNumber'] = $tenderNumber;
        $documentsData[$key]['Name'] = trim($aDocumentTag->text());
        $documentsData[$key]['Link'] = $prefix . $aDocumentTag->attr("href");
    }
    return $documentsData;
}

function saveTenderDataInDB(\myClss\Db $db, array $tender): void
{
    $sql = "INSERT INTO Tenders (`TenderNumber`,`OrganizerName`,`TenderViewUrl`,`RequestReceivingBeginDate`) VALUES (:TenderNumber, :OrganizerName, :TenderViewUrl, :RequestReceivingBeginDate)";
    $db->rawSql($sql, $tender);
}

function saveTenderDocumentsInDB(\myClss\Db $db, array $documents): void
{
    $sql = "INSERT INTO Documents (`Tender_TenderNumber`, `Name`, `Link`) VALUES (:Tender_TenderNumber, :Name, :Link)";
    foreach ($documents as $document) {
        $db->rawSql($sql, $document);
    }
}

function saveTenderInDB(\myClss\Db $db, array $tenderData, array $tenderDocuments): void
{
    saveTenderDataInDB($db, $tenderData);
    saveTenderDocumentsInDB($db, $tenderDocuments);
}

function clearDB(\myClss\Db $db): void
{
    $sql = "DELETE FROM Tenders";
    $db->rawSql($sql);
}