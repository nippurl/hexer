<?php

namespace App\Tests\Controller;

use App\Tests\PantherBase;
use PHPUnit\Framework\Assert;
use Symfony\Component\Panther\PantherTestCase;

class AppControllerTest extends PantherBase
{
    public function testIndex(): void
    {
        $client = $this->Client();
        $crawler = $client->request('GET', '/');
				Assert::assertTrue($crawler->filter('h1#bienvenido')->count() > 0);
			//	$this->echo($crawler->filter('td')->text());
       // $this->assertSelectorTextContains('h1', 'Bienvenido');
		    Assert::assertEquals('Moto', $crawler->filter('td')->text());
    }
		public function testFiltro() {
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertTrue($crawler->filter('h1')->count() > 0);
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				Assert::assertEquals('Moto', $crawler->filter('td')->text());

				$form = $crawler->selectButton('Filtrar')->form();
				$crawler = $client->submit($form, [
						'q' => 'Focus',
				]);
				Assert::assertTrue($crawler->filter('h1')->count() > 0);
				Assert::assertEquals('Auto', $crawler->filter('td')->text());
		}
		public function testNuevoAuto (){
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertTrue($crawler->filter('h1')->count() > 0);
				// Pasar a unoo nuevo
				$crawler = $client->clickLink('Nuevo Vehiculo');
				Assert::assertEquals('Nuevo Vehiculo', $crawler->filter('h1')->text());
				$form = $crawler->selectButton('Guardar')->form();
				$form['vehiculo[tipo]']->select('a');
				$form['vehiculo[marca]'] = 'Ferrari';
				$form['vehiculo[modelo]'] = 'Testa Rosa';
				$form['vehiculo[color]'] = 'Rosa';
				$form['vehiculo[matricula]'] = 'AAA111';
				$crawler = $client->submit($form);
				// Error por matricula erronia
				//$client->waitFor("input[value='Guardar']", 10);
				Assert::assertEquals('Nuevo Vehiculo', $crawler->filter('h1')->text());
				$form = $crawler->selectButton('Guardar')->form();
				$form['vehiculo[matricula]'] = 'AA1111';
				$crawler = $client->submit($form);
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				$form = $crawler->selectButton('Filtrar')->form();
				$crawler = $client->submit($form, [
					'q' => 'Ferrari',
				]);
				Assert::assertTrue($crawler->filter('h1')->count() > 0);
				Assert::assertEquals('Auto', $crawler->filter('td')->text());
		}
		public function testNuevoMoto (){
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertTrue($crawler->filter('h1')->count() > 0);
				// Pasar a unoo nuevo
				$crawler = $client->clickLink('Nuevo Vehiculo');
				Assert::assertEquals('Nuevo Vehiculo', $crawler->filter('h1')->text());
				$form = $crawler->selectButton('Guardar')->form();
				$form['vehiculo[tipo]']->select('m');
				$form['vehiculo[marca]'] = 'HD';
				$form['vehiculo[modelo]'] = 'chopera';
				$form['vehiculo[color]'] = 'negra';
				$form['vehiculo[matricula]'] = 'AA1111';
				$crawler = $client->submit($form);
				// Error por matricula erronia
				//$client->waitFor("input[value='Guardar']", 10);
				Assert::assertEquals('Nuevo Vehiculo', $crawler->filter('h1')->text());
				$form = $crawler->selectButton('Guardar')->form();
				$form['vehiculo[matricula]'] = '111A';
				$crawler = $client->submit($form);
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				$form = $crawler->selectButton('Filtrar')->form();
				$crawler = $client->submit($form, [
					'q' => 'chopera',
				]);
				Assert::assertTrue($crawler->filter('h1')->count() > 0);
				Assert::assertEquals('Moto', $crawler->filter('td')->text());
		}
		public function testShow ():void{
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				//elegir el auto id 3
				$a = $crawler->filter('#ver_3');
				/** @var \Facebook\WebDriver\Remote\RemoteWebElement $Remote */
				$a->click();
				$crawler = $client->waitFor('table#vehiculo_3');
				
				//$crawler = $client->click($a);
				$h1 = $crawler->filter('h1')->text();
				Assert::assertEquals('Ford (AA1234)', $h1);
				$crawler = $client->clickLink('Volver');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
		}
		
		public function testEdit ():void{
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				//elegir el auto id 3
				$a = $crawler->filter('#edit_3');
				$response = $a->click();
//				if ($response->statusCode() !== 301) {
//					return;
//
//				}
				$crawler = $client->waitFor('input#vehiculo_matricula');
				$form = $crawler->selectButton('Guardar')->form();
				$form['vehiculo[tipo]']->select('a');
				$form['vehiculo[matricula]'] = 'AA1235';
				$form['vehiculo[modelo]'] = 'Mustang';
				$crawler = $client->submit($form);
				//$crawler = $client->click($a);
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				$a = $crawler->filter('#ver_3');
				/** @var \Facebook\WebDriver\Remote\RemoteWebElement $Remote */
				$a->click();
				$crawler = $client->waitFor('table#vehiculo_3');
				
				//$crawler = $client->click($a);
				$h1 = $crawler->filter('h1')->text();
				Assert::assertEquals('Ford (AA1235)', $h1);
				$crawler = $client->clickLink('Volver');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				// ver los cambios fueron validos
				
		}
		
		/**
		 * Debe abrir y al auto 3 y rematricularlo y termina show con la nueva matricula
		 * @return void
		 */
		public function testRematricularAuto ():void{
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				//elegir el auto id 3
				$a = $crawler->filter('#rematricular_3');
				/** @var \Facebook\WebDriver\Remote\RemoteWebElement $Remote */
				$a->click();
				$crawler = $client->waitFor('table#vehiculo_3');
				$matricula  =  $crawler->filter('td#matricula')->text();
				Assert::assertNotEquals('AA1234', $matricula);
				Assert::assertEquals(6, strlen($matricula), 'La nueva matrucula no tiene 6 digitos para el AUTO');
				
		}
		public function testRematricularMoto ():void{
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				//elegir el auto id 3
				$a = $crawler->filter('#rematricular_1');
				/** @var \Facebook\WebDriver\Remote\RemoteWebElement $Remote */
				$a->click();
				$crawler = $client->waitFor('table#vehiculo_1');
				$matricula  =  $crawler->filter('td#matricula')->text();
				Assert::assertNotEquals('123A', $matricula);
				Assert::assertEquals(4, strlen($matricula), 'La nueva matrucula no tiene 4 digitos para la MOTO');
		}
		
		public function testVenderAuto ():void{
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				//elegir el auto id 3
				$a = $crawler->filter('a#vender_3');
				$a->click();
				//$client->getWebDriver()->switchTo()->defaultContent()->sendKeys('ENTER');
				//$client->getWebDriver()->switchTo()->alert()->accept();
		//		$client->getWebDriver()->switchTo()->defaultContent()->ok();
				$crawler = $client->waitFor('h1#bienvenido');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				// debe estar el 1 pero no el 3 porque fue vendido
				$a = $crawler->filter('#ver_1');
				Assert::assertEquals(1, $a->count(), 'El auto 1 sigue estando');
	//			$client->takeScreenshot(__DIR__.'/../imagens/error.png');
				$a = $crawler->filter('#ver_3');
				Assert::assertEquals(0, $a->count(), 'El auto 3 ya fue vendido y sigue estando');
				
		}
		public function testVenderMoto ():void{
				$client = $this->Client();
				$crawler = $client->request('GET', '/');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				//elegir el auto id 3
				$a = $crawler->filter('a#vender_1');
				$a->click();
				//$client->getWebDriver()->switchTo()->alert()->accept();
				$crawler = $client->waitFor('h1#bienvenido');
				Assert::assertEquals('Bienvenido', $crawler->filter('h1')->text());
				// debe estar el 1 pero no el 3 porque fue vendido
				$a = $crawler->filter('a#ver_3');
				Assert::assertEquals(1, $a->count(), 'El vehiculo 1 sigue estando');
				$a = $crawler->filter('a#ver_1');
				Assert::assertEquals(0, $a->count(), 'El vehiculo 3 ya fue vendido y sigue estando');
				
		}
		
		
		public function setUp ():void{
				parent::setUp();
				$this->cleanDB();
				$this->cleanCache();
		}
		private function cleanDB():void
		{
				$cmd    = 'php /home/www/hexerAPI/bin/console h:f:l  --env=dev -n  --purge-with-truncate'; # --env=test';
				$output = shell_exec($cmd);
		}
		
		private function cleanCache():void
		{
				$cmd    = 'sudo rm -rf /home/www/hexer/var/cache/* '; # --env=test';
				$output = shell_exec($cmd);
		}
		
}
