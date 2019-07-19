<?php

namespace Testiat;

class Api
{
    private const API_ENPOINT = 'https://testi.at/UAPI';
    private const VERSION = '1.0.0';
    private const DESCRIPTION = 'testi@ API client for PHP';
    private const INTRO = '
     ████████╗███████╗███████╗████████╗██╗    █████╗ ████████╗
     ╚══██╔══╝██╔════╝██╔════╝╚══██╔══╝██║   ██╔══██╗╚══██╔══╝
        ██║   █████╗  ███████╗   ██║   ██║   ███████║   ██║ 
        ██║   ██╔══╝  ╚════██║   ██║   ██║   ██╔══██║   ██║   
        ██║   ███████╗███████║   ██║   ██║██╗██║  ██║   ██║   
        ╚═╝   ╚══════╝╚══════╝   ╚═╝   ╚═╝╚═╝╚═╝  ╚═╝   ╚═╝ 
    ';

    private const API_KEY = '';

    public function __construct() {

        echo self::INTRO;
        echo '
        '.self::DESCRIPTION.'
        '.self::VERSION.'
        ';

        if (
            count(getopt('', ['apikey::'])) === 0 &&
            !getenv('TESTIAT_APIKEY', true)
        ) {
            echo 'Please provide an API key.';
            exit(1);
        }

        $this->apikey = getenv('TESTIAT_APIKEY', true)
            ? getenv('TESTIAT_APIKEY', true)
            : isset(getopt('', ['apikey::'])['apikey'])
                ? getopt('', ['apikey::'])['apikey']
                : self::API_KEY;

    }

    private function createRequest($queryArray, $path) {
        $postFields = http_build_query(
            array_merge(
                [
                    'API' => $this->apikey
                ],
                $queryArray
            )
        );
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL, self::API_ENPOINT . $path);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$response = curl_exec($ch);
		
		curl_close($ch);
		
		if(!$response){
			return false;
		}
		return json_decode($response);
    }

    public function getAvailableClients() {
        return self::createRequest([], '/listEmlClients');
    }

    public function getProjectStatus($id) {
        if (
            !$id ||
            gettype($id) !== 'string'
        ) {
            echo 'Please provide a valid project ID.';
            exit(1);
        }

        return self::createRequest([
            'ProjID' => $id
        ], '/projStatus');
    }

    public function startEmailTest($subject, $html, $clients) {
        if (
            !$subject ||
            !$html ||
            !$clients
        ) {
            echo 'Please provide subject, html and client list.';
            exit(1);
        }

        if (
            !is_array($clients) ||
            count($clients) === 0
        ) {
            echo 'Please provide at least one client as array.';
            exit(1);
        }

        return self::createRequest([
            'Subject' => $subject,
            'HTML' => $html,
            'ECID' => $clients
        ], '/letsgo');
    }
}

