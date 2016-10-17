<?php

namespace Unforgivencl\LaraChileanLaw\LawsApi\Endpoints;

class Law extends AbstractEndpoint
{
	public function paginate($pages)
	{
		return $this->setParam('pages', $pages);
	}

	public function content($content)
	{
		return $this->setParam('content', $content);
	}

	public function number($number)
	{
		return $this->setParam('number', $number);
	}

	public function getMostSearched()
	{
		return $this->setHttpMethod('GET')
			->setUriGenerator(function ($params) {
				return implode('', [
					'opt=31',
					'&agr=2',
				]);
			});
	}

	public function getLatestSpecific()
	{
		return $this->setHttpMethod('GET')
			->setUriGenerator(function ($params) {
				return implode('', [
					'opt=7',
					'&idNorma='.array_get($params, 'number'),
				]);
			});
	}

	public function getLatestPublished()
	{
		return $this->setHttpMethod('GET')
			->setUriGenerator(function ($params) {
				return implode('', [
					'opt=3',
					'&cantidad='.array_get($params, 'pages'),
				]);
			});
	}

	public function getByContent()
	{
		return $this->setHttpMethod('GET')
			->setUriGenerator(function ($params) {
				return implode('', [
					'opt=61',
					'&cadena='.array_get($params, 'content'),
					'&cantidad='.array_get($params, 'pages'),
				]);
			});
	}
}
