<?php

namespace Unforgivencl\LaraChileanLaw\LawsApi;

use GuzzleHttp\ClientInterface;
use ErrorException;

class ApiRequest
{
	protected $client;

	public function __construct(ClientInterface $client)
	{
		$this->client = $client;
	}

	public function __call($name, $args)
	{
		$map = [
			'law' => Endpoints\Law::class,
		];

		if (!isset($map[$name])) {
			throw new ErrorException("Endpoint `$name` not found");
		}

		return new $map[$name]($this->client, $args);
	}
}
