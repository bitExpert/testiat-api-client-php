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

    /**
     * @return ResponseInterface|null
     */
    public function getAvailableClients(): ?ResponseInterface
    {
        return $this->apiRequest([], '/listEmlClients');
    }

    /**
     * @param string $id
     * @return ResponseInterface|null
     */
    public function getProjectStatus(string $id): ?ResponseInterface
    {
        return $this->apiRequest(
            [
                'ProjID' => $id
            ],
            '/projStatus'
        );
    }

    /**
     * @param string $subject
     * @param string $html
     * @param array $clients
     * @return ResponseInterface|null
     */
    public function startEmailTest(string $subject, string $html, array $clients): ?ResponseInterface
    {
        return $this->apiRequest(
            [
                'Subject' => $subject,
                'HTML' => $html,
                'ECID' => $clients
            ],
            '/letsgo'
        );
    }

    /**
     * @param array $queryArray
     * @param string $path
     * @return ResponseInterface|null
     */
    private function apiRequest(array $queryArray, string $path): ?ResponseInterface
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
