<?php

namespace Unforgivencl\LaraChileanLaw\LawsApi\Endpoints;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Collection;
use GuzzleHttp\Psr7\Request;

abstract class AbstractEndpoint
{
    protected $client;
    protected $params = [];
    protected $uriGenerator;
    protected $httpMethod = 'GET';

    protected $errorCodes = [
        500 => \Unforgivencl\LaraChileanLaw\LawsApi\Exceptions\InternalServerErrorException::class,
    ];

    public function __construct(ClientInterface $client, $args = [])
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set values for parameter to generate endpoint.
     *
     * @param string $key   Name of params
     * @param mixed  $value Its value
     *
     * @return AbstractEndpoint
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Define which HTTP method should be sent, as well as how to generate
     * URI of resource.
     *
     * @param string   $method       Either GET POST PUT DELETE
     * @param callable $uriGenerator
     *
     * @return AbstractRequest
     */
    public function setHttpMethod($method, callable $uriGenerator = null)
    {
        $this->httpMethod = $method;

        if ($uriGenerator !== null) {
            $this->setUriGenerator($uriGenerator);
        }

        return $this;
    }

    public function setUriGenerator(callable $uriGenerator)
    {
        $this->uriGenerator = $uriGenerator;

        return $this;
    }

    /**
     * Subclasses will define how URI of an endpoint is generated.
     *
     * @return string
     */
    public function generateResourceUri()
    {
        if ($this->uriGenerator === null) {
            throw new \ErrorException('Dont know how to generate URL. Maybe resources were missing?');
        }

        return call_user_func($this->uriGenerator, $this->params);
    }

    public function getUrl(array $queryString = [])
    {
        return 'https://www.'
            .'leychile.cl/Consulta/obtxml?'
            .$this->generateResourceUri()
            .http_build_query($queryString);
    }

    /**
     * Send HTTP request to Chilean Law API.
     *
     * @param array $queryParameters Optional query string data
     *
     * @return array                         If request a single entry
     * @return Illuminate\Support\Collection If request a collection of resources
     */
    public function fetch(array $queryParameters = [])
    {
        $url = $this->getUrl($queryParameters);

        try {
            $result = $this->client->request($this->httpMethod, $url);

            $data = $result->getBody();

            $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

            $json = json_encode($xml);

            $json_response = json_decode($json, true);

            if (isset($json_response[0]) && is_array($json_response[0])) {
                return new Collection($json_response);
            }

            return $json_response;
        } catch (ServerException $ex) {
            $response = $ex->getResponse();

            return $this->handleError($response);
        }
    }

    /**
     * Children classes have to define how to handle with error.
     * Throwing exceptions for example.
     *
     * @param  $response Response returned from LeyChile WS
     */
    public function handleError($response)
    {
        $code = $response->getStatusCode();
        if (!isset($this->errorCodes[$code])) {
            throw new \ErrorException('Unsupported error for status code: '.$code);
        }
        $ex = new $this->errorCodes[$code]();
        $ex->response = $response;
        throw $ex;
    }
}
