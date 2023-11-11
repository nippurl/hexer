<?php

namespace App\Util;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * En todas las consultas
 * @author
 * @return array
 *              'resultado' => 'ok' si es ok, ERROR si hay un error
 *              'error'=> Mensaje de error,
 *              'vehiculo' => Como quedo el objeto creado o modificado
 */
class HexerApiService implements HexerApiInterface
{
    /**
     * Tipos de Vehiculos soportados
     *  @var array
     */
    public const VehiculoTipo = [
      'Auto' => 'a',
      'Moto' => 'm',
    ];
    private const URL = 'http://127.0.0.1:8000';
    
    public function __construct(private readonly HttpClientInterface $client)
    {
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function edit($id, $data): array
    {
        $url = self::URL.'/'.$id.'/edit';
        $response = [
          'error' => 'NO pudo ejecutar el EDIT'
        ];
        try {
            $response = $this->client->request('GET', $url, [
              'json' => $data,
            ]);
            if ($response->getStatusCode() === 200) {
                return $response->toArray();
            }
        } catch (TransportExceptionInterface|ClientExceptionInterface|DecodingExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface $e) {
        }
        return $this->error($response->getContent());
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public
    function findAll(): array
    {
        $url      = self::URL.'/';
        $response = $this->client->request('GET', $url);
        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
        //\var_dump($response->getContent());
        return $this->error($response->toArray());
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public
    function findQuery(
      $text
    ): array {
        $url      = self::URL.'/';
        $response = $this->client->request(
          'GET', $url,
          [
            'query' => [
              'q' => $text,
            ],
          ]
        );
        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
        //\var_dump($response->getContent());
        return $this->error($response->toArray());
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public
    function newAuto(
      array $data
    ): array {
        $url      = self::URL.'/new/a';
        $response = $this->client->request(
          'GET', $url,
          [
            'json' => $data,
          ]
        );
        if ($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
            return $response->toArray();
        }
        //	\var_dump($response->getContent());
        return $this->error($data);
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public
    function newMoto(
      array $data
    ): array {
        $response = $this->client->request(
          'GET',
          self::URL.'/new/m',
          [
            'json' => $data,
          ]
        );
        if ($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
            return $response->toArray();
        }
        //	\var_dump($response->getContent());
        return $this->error($data);
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public
    function rematricular(
      $id
    ): array {
        $url      = self::URL.'/'.$id.'/rematricular';
        $response = $this->client->request('GET', $url);
        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
        return $this->error($response->getContent());
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public
    function show(
      $id
    ): array {
        $url      = self::URL.'/'.$id.'/ver';
        $response = $this->client->request('GET', $url);
        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
        //\var_dump($response->getContent());
        return $this->error($response->toArray());
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public
    function vender(
      $id
    ): array {
        $url      = self::URL.'/'.$id.'/vender';
        $response = $this->client->request('GET', $url);
        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
        return $this->error($response->getContent());
    }
    
    /**
     * Se eejcuta cuando hay un error en la peticion
     *
     * @param $data
     * @return array
     */
    private
    function error(
      array $data,
      ?Exception $exception =null
    ): array {
        $respuesta              = [];
        $respuesta['resultado'] = 'ERROR';
        $respuesta['data']= $data;
        if (\array_key_exists('error',$data)) {
            $respuesta['error'] = $data['error'];
        }else{
            if ($exception) {
                $respuesta['error'] = $exception->getMessage();
            }else{
                $respuesta['error'] = 'Error desconocido';
            }
        }
        return $respuesta;
    }
    
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public
    function verificar(
      $data
    ): array {
        $errores  = [];
        $url      = self::URL.'/verificar';
        $response = $this->client->request('GET', $url, [
          'json' => $data,
        ]);
        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
        return $this->error($response->getContent());
    }
}