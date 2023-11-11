<?php

namespace App\Tests\Util;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Util\HexerApiInterface;
use App\Util\HexerApiService;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HexerApiServiceTest extends ApiTestCase
{
		private HexerApiInterface $hexerApiService;
		
		public function testFindAll()
		{
				$respuesta = $this->hexerApiService->findAll();
				//\var_dump($respuesta);
				$this->assertIsArray($respuesta);
				$this->assertNotEmpty($respuesta);
				$this->assertEquals(2, count($respuesta), 'No hay 2 elementos');
				$obj = $respuesta[0];
				$this->assertEquals('Yamaha', $obj['marca']);
		}
		
		public function testFindQuery()
		{
				//DEbe encontra a yAMAHA
				$respuesta = $this->hexerApiService->findQuery('amaha');
				$this->assertIsArray($respuesta);
				$this->assertNotEmpty($respuesta);
				if (count($respuesta) !== 1) {
						\var_dump($respuesta);
				}
				$this->assertEquals(1, count($respuesta), 'No hay 1 elemento');
				$obj = $respuesta[0];
				$this->assertEquals('Yamaha', $obj['marca']);
				
		}
		
		public function testNewAuto()
		{
				$vehiculo  = [
					'marca'     => 'Renault',
					'modelo'    => '12',
					'color'     => 'gris',
					'matricula' => 'VI2086',
				];
				$respuesta = $this->hexerApiService->newAuto($vehiculo);
				$this->assertIsArray($respuesta);
				$resultado = $respuesta['resultado'];
        if ('ok' !== $resultado) {
            \var_dump($respuesta);
        }
				$this->assertEquals('ok', $resultado);
				$vehiculo = $respuesta['vehiculo'];
				$this->assertEquals('Renault', $vehiculo['marca']);
				$this->assertEquals('12', $vehiculo['modelo']);
				$this->assertEquals('gris', $vehiculo['color']);
				$this->assertEquals('VI2086', $vehiculo['matricula']);
				$this->assertEquals(null, $vehiculo['venta']);
		}
		
		public function testNewMoto()
		{
				$vehiculo  = [
					'marca'     => 'Kawasaki',
					'modelo'    => 'Ninja 250',
					'color'     => 'Negra',
					'matricula' => '250N',
				];
				$respuesta = $this->hexerApiService->newMoto($vehiculo);
				$resultado = $respuesta['resultado'];
        if ('ok' !== $resultado) {
            \var_dump($respuesta);
        }
				$this->assertEquals('ok', $resultado);
				$vehiculo = $respuesta['vehiculo'];
				$this->assertEquals('Kawasaki', $vehiculo['marca']);
				$this->assertEquals('Ninja 250', $vehiculo['modelo']);
				$this->assertEquals('Negra', $vehiculo['color']);
				$this->assertEquals('250N', $vehiculo['matricula']);
				$this->assertEquals(null, $vehiculo['venta']);
		}
		
		public function testVer()
		{
				$response = $this->hexerApiService->show(3);
				$vehiculo = $response;
				//\var_dump($resultado);
				$this->assertEquals('Ford', $vehiculo['marca']);
				$this->assertEquals('Auto', $vehiculo['tipo']);
		}
		
		public function testEdit()
		{
				$vehiculo  = [
					'marca'     => 'Renault',
					'modelo'    => '12',
					'color'     => 'gris',
					'matricula' => 'VI1986',
						//		'venta'     => null,
				];
				$response  = $this->hexerApiService->edit(3, $vehiculo);
				$resultado = $response['resultado'];
				$vehiculo  = $response['vehiculo'];
				//\var_dump($resultado);
				$this->assertEquals('ok', $resultado);
				$this->assertEquals('Renault', $vehiculo['marca']);
				$this->assertEquals('12', $vehiculo['modelo']);
				$this->assertEquals('gris', $vehiculo['color']);
				$this->assertEquals('VI1986', $vehiculo['matricula']);
				$this->assertEquals(null, $vehiculo['venta']);
		}
		
		public function testEditarNoDispponible()
		{
				 $vehiculo = [
					'marca'     => 'Renault',
					'modelo'    => '12',
					'color'     => 'gris',
					'matricula' => 'VI1986',
						//		'venta'     => null,
				];
				$response  = $this->hexerApiService->edit(4, $vehiculo);
				$resultado = $response['resultado'];
				$vehiculo  = $response['vehiculo'];
				//\var_dump($resultado);
				$this->assertEquals('ERROR', $resultado);
				$this->assertEquals('No se puede guardar un vehiculo que ya no disponible', $response['error']);
				
		}
		
		public function testRematricularMoto()
		{
				$id        = 1;
				$response  = $this->hexerApiService->rematricular($id);
				$resultado = $response['resultado'];
				$vehiculo  = $response['vehiculo'];
				$this->assertEquals('ok', $resultado);
				$this->assertEquals('Yamaha', $vehiculo['marca']);
				$this->assertEquals('cross', $vehiculo['modelo']);
				$this->assertNotEquals('123A', $vehiculo['matricula']);
		}
		
		
		public function testRematricularAuto()
		{
				// Chequeo Original
				$id            = 3;
				$response  = $this->hexerApiService->rematricular($id);
				$resultado = $response['resultado'];
				$vehiculo  = $response['vehiculo'];
				$this->assertEquals('ok', $resultado);
				$this->assertEquals('Ford', $vehiculo['marca']);
				$this->assertEquals('Auto', $vehiculo['tipo']);
				$this->assertNotEquals('AA1234', $vehiculo['matricula']);
		}
		public function testVender()
		{
				// Chequeo Original
				$id = 1;
				$resultado  = $this->hexerApiService->vender($id);
				$this->assertEquals('ok', $resultado['resultado']);
				$vehiculo = $resultado['vehiculo'];
				$this->assertEquals('Yamaha', $vehiculo['marca']);
				$this->assertEquals('cross', $vehiculo['modelo']);
				$this->assertNotNull($vehiculo['venta']);
		}
		
		
		protected function setUp(): void
		{
				parent::setUp();
				//		$this->$kernel                = self::bootKernel();
				$client                = self::getContainer()
				                             ->get(HttpClientInterface::class);
				$this->hexerApiService = new HexerApiService($client);
				/// Limpiar la base de datos del API_test
				$cmd    = 'php /home/www/hexerAPI/bin/console h:f:l  --env=dev -n  --purge-with-truncate'; # --env=test';
				$output = shell_exec($cmd);
				//	echo "<pre>$output</pre>";
				
		}
}

