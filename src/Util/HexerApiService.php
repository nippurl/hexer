<?php

namespace App\Util;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class HexerApiService implements HexerApiInterface
{
		private const URL = 'http://127.0.0.1:8000';
		
		public function __construct(
			private HttpClientInterface $client
		) {
		}
		
		public function edit($id, $data)
		{
				$url      = self::URL.'/'.$id.'/edit';
				$response = $this->client->request('GET', $url, [
					'json' => $data,
				]);
				if ($response->getStatusCode() === 200) {
						$data = $response->toArray();
						return $data;
				} else {
						return $this->error($response->getContent());
				}
		}
		
		public function findAll()
		{
				$url      = self::URL.'/';
				$response = $this->client->request('GET', $url);
				if ($response->getStatusCode() === 200) {
						$data = $response->toArray();
						return $data;
				} else {
						\var_dump($response->getContent());
						return false;
				}
				
		}
		
		public function findQuery($text)
		{
				$url      = self::URL.'/';
				$response = $this->client->request('GET', $url,
					[
						'query' => [
							'q' => $text,
						],
					]
				);
				if ($response->getStatusCode() === 200) {
						$data = $response->toArray();
						return $data;
				} else {
						\var_dump($response->getContent());
						return false;
				}
		}
		
		public function newAuto(array $data)
		{
				$url      = self::URL.'/new/a';
				$response = $this->client->request('GET', $url,
					[
						'json' => $data,
					]
				);
				if ($response->getStatusCode() === 201) {
						$data = $response->toArray();
						return $data;
				} else {
						\var_dump($response->getContent());
						return $data;
				}
		}
		
		public function newMoto(array $data)
		{
				$response = $this->client->request(
					'GET',
					self::URL.'/new/m',
					[
						'json' => $data,
					]
				);
				if ($response->getStatusCode() === 201) {
						$data = $response->toArray();
						return $data;
				} else {
						\var_dump($response->getContent());
						return $data;
				}
		}
		
		public function rematricular($id)
		{
				$url      = self::URL.'/'.$id.'/rematricular';
				$response = $this->client->request('GET', $url);
				if ($response->getStatusCode() === 200) {
						$data = $response->toArray();
						return $data;
				} else {
						return $this->error($response->getContent());
				}
		}
		
		public function show($id)
		{
				$url      = self::URL.'/'.$id.'/ver';
				$response = $this->client->request('GET', $url);
				if ($response->getStatusCode() === 200) {
						$data = $response->toArray();
						return $data;
				} else {
						\var_dump($response->getContent());
						return false;
				}
		}
		
		public function vender($id)
		{
				$url      = self::URL.'/'.$id.'/vender';
				$response = $this->client->request('GET', $url);
				if ($response->getStatusCode() === 200) {
						$data = $response->toArray();
						return $data;
				} else {
						return $this->error($response->getContent());
				}
		}
		
		/**
		 * Se eejcuta cuando hay un error en la peticion
		 *
		 * @param $data
		 * @return void
		 */
		private function error($data) :array
		{
				$respuesta              = [];
				$respuesta['resultado'] = 'ERROR';
				$respuesta['error']     = $data['error'];
				return $respuesta;
		}
	
}