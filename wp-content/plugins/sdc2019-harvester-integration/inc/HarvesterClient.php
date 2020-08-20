<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;

class HarvesterClient {
    protected $baseUri = "https://www.conferenceharvester.com/conferenceportal3/webservices/HarvesterJsonAPI.asp";
    protected $key = "ZJ2npb1lIUo9";
    protected $client;

    function __construct()
    {
        $this->client = new Client([
            'base_uri' => '',
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    function request($params)
    {
        try {
        	$params['APIKey'] = $this->key;
            return $this->client->request('GET', $this->baseUri, [
                'query' => $params,
            ]);
        } catch(TransferException $e) {
            Log::record(['Message' => $e->getMessage(), 'File' => $e->getFile(), 'Line' => $e->getLine(), 'Trace' => $e->getTraceAsString()]);
            return null;
        }
    }

    function getPresentationCount()
    {
        $response = $this->request(['Method' => 'getPresentationCount']);
        $body = json_decode($response->getBody(), true);
        $count = $body[0]['Count'];
        return $count;
    }

    function getPresentationData()
	{
		// pull fresh speaker data
		$data = "";

		// determine whether we should paginate requests based on presentation count
		$count = $this->getPresentationCount();
		$records_per_page = 100;
		$pagecount = ceil($count / $records_per_page);

		try {
			for($i = 0; $i < $pagecount; $i++) {
				$start = $i * $records_per_page;
				$end = $start + ($records_per_page - 1);
				$response = $this->request([
					'Method' => 'getPresentationsWithPresenters',
					'Between' => $start . '-' . $end,
					'stream' => true,
//			'NullFilterField' => 'PresenterTwitterHandle',
				]);

				// Read bytes off of the stream until the end of the stream is reached
				$body = $response->getBody();

				while (!$body->eof()) {
					$data .= $body->read(1024);
				}
			}
		} catch(RequestException $e) {
			return json_encode([
				'error' => $e,
			]);
		}

		return $data;
	}

	function storePresentationData()
	{
		// get presentation data
		$data = $this->getPresentationData();

		// store fetched data to file
		$cachefile = __DIR__ . '/presentationcache';
		$open = fopen( $cachefile, "w" );
		$write = fputs($open, $data);
		fclose($open);

		$result = [
			'success' => true,
			];
		return json_encode($result);
	}

	function refreshCachefile()
	{
		// refresh data cache
		$this->storePresentationData();
	}

    function getPresentersData()
    {
        $response = $this->request(['Method' => 'getPresenters']);
        $body = $response->getBody();
        return $body;
    }

    function storePresentersData()
    {
        $data = $this->getPresentersData();
        // store fetched data to file
        $cachefile = __DIR__ . '/presenterscache';
        $open = fopen( $cachefile, "w" );
        $write = fputs($open, $data);
        fclose($open);

        $result = [
            'success' => true,
            ];
        return json_encode($result);
    }
    function refreshPresentersCache()
    {
        $this->storePresentersData();
    }

	public static function listTopics()
	{
		$cachefile = __DIR__ . '/presentationcache';
		$contents = file_get_contents( $cachefile );
		$json = json_decode($contents);
		$data = [];
		foreach($json as $session => $array) {
			if ($array->CustomPresField2) {
				if (!in_array($array->CustomPresField2, $data)) {
					$data[$array->ROW] = $array->CustomPresField2;
				}
			}
		}

		return json_encode($data);
	}

    public static function getSessionData($sessionId)
    {
        $cachefile = __DIR__ . '/presentationcache';
        $contents = file_get_contents( $cachefile );
        $json = json_decode($contents);
        $data = null;
        foreach( $json as $k => $v ){
            if( $v->PresentationID == $sessionId ){
                $data = $v;
                break;
            }
        }

        return $data;
    }

    public static function getSpeakerData($speakerId)
    {
        $cachefile = __DIR__ . '/presenterscache';
        $contents = file_get_contents( $cachefile );
        $json = json_decode($contents);
        $data = null;
        foreach( $json as $k => $v ){
            if( $v->PresenterID == $speakerId ){
                $data = $v;
                break;
            }
        }

        return $data;
    }
}
