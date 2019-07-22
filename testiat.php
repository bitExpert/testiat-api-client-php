<?php
declare(strict_types=1);

namespace bitExpert\Testiat;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

require 'vendor/autoload.php';

class Api
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var RequestFactoryInterface
     */
    protected $factory;

    /**
     * @var string
     */
    private $apikey;

    private const API_ENPOINT = 'https://testi.at/UAPI';

    private const API_KEY = '';

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $factory,
        string $apikey
    ) {
        $this->client = $client;
        $this->factory = $factory;
        $this->apikey = $apikey;
    }

    private function createRequest(array $queryArray, string $path) {
        $postFields = http_build_query(
            array_merge(
                [
                    'API' => $this->apikey
                ],
                $queryArray
            )
        );

        $request = ($this->factory->createRequest(
            'POST',
            self::API_ENPOINT . $path
        ));

        $request->getBody()->write($postFields);

        $response = $this->client->sendRequest($request);

        if(!$response){
            return false;
        }

        return json_decode((string) $response->getBody(), true);
    }

    public function getAvailableClients(): array {
        return self::createRequest([], '/listEmlClients');
    }

    public function getProjectStatus(string $id): array {
        if (
            !$id ||
            gettype($id) !== 'string'
        ) {
            return 'Please provide a valid project ID.';
        }

        return self::createRequest([
            'ProjID' => $id
        ], '/projStatus');
    }

    public function startEmailTest(string $subject, string $html, array $clients): array {
        if (
            !$subject ||
            !$html ||
            !$clients
        ) {
            return 'Please provide subject, html and client list.';
        }

        if (
            !is_array($clients) ||
            count($clients) === 0
        ) {
            return 'Please provide at least one client as array.';
        }

        return self::createRequest([
            'Subject' => $subject,
            'HTML' => $html,
            'ECID' => $clients
        ], '/letsgo');
    }
}

