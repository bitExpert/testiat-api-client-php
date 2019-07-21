<?php
declare(strict_types=1);

namespace bitExpert\Testiat;

use Psr\Http\Client\ClientInterface;

require 'vendor/autoload.php';

class Api
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    private $apikey;

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

    public function __construct(
        ClientInterface $client
    ) {
        $this->client = $client;

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

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $body = $psr17Factory->createStream($postFields);

        $request = ($psr17Factory->createRequest(
            'POST',
            self::API_ENPOINT . $path
        ))->withBody($body);

        $response = $this->client->sendRequest($request);

        if(!$response){
            return false;
        }

        return json_decode((string) $response->getBody(), true);
    }

    public function getAvailableClients(): array {
        return self::createRequest([], '/listEmlClients');
    }

    public function getProjectStatus($id): array {
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

    public function startEmailTest($subject, $html, $clients): array {
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

