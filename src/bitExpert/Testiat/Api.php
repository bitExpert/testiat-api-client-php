<?php
declare(strict_types=1);

namespace bitExpert\Testiat;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

require 'vendor/autoload.php';

class Api
{
    private const API_ENPOINT = 'https://testi.at/UAPI';
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

    /**
     * Api constructor.
     *
     * @param ClientInterface $client
     * @param RequestFactoryInterface $factory
     * @param string $apikey
     */
    public function __construct(ClientInterface $client, RequestFactoryInterface $factory, string $apikey)
    {
        $this->client = $client;
        $this->factory = $factory;
        $this->apikey = $apikey;
    }

    public function getAvailableClients(): ?ResponseInterface
    {
        return $this->createRequest([], '/listEmlClients');
    }

    public function getProjectStatus(string $id): ?ResponseInterface
    {
        return $this->createRequest(
            [
                'ProjID' => $id
            ],
            '/projStatus'
        );
    }

    public function startEmailTest(string $subject, string $html, array $clients): ?ResponseInterface
    {
        return $this->createRequest(
            [
                'Subject' => $subject,
                'HTML' => $html,
                'ECID' => $clients
            ],
            '/letsgo'
        );
    }

    private function createRequest(array $queryArray, string $path): ?ResponseInterface
    {
        try {
            $request = $this->factory->createRequest(
                'POST',
                self::API_ENPOINT . $path
            );
            $queryArray = array_merge(['API' => $this->apikey], $queryArray);
            $postFields = http_build_query($queryArray);
            $request->getBody()->write($postFields);

            return $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
        }

        return null;
    }
}
