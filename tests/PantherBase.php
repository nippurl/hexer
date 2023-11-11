<?php

namespace App\Tests;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCase;

abstract class PantherBase extends PantherTestCase
{
    //	use RecreateDatabaseTrait;
    
    //const PATH = './var/errors/';
    const HTTP_HEXER_LOCALHOST = 'http://hexer.localhost';
    protected static ?Client $client = null;
    
    /**
     * Crea la el Client
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->takeScreenshotIfTestFailed();
        $this->Client();
        $this->cleanDB();
        $this->cleanCache();
    }
    
    protected function tearDown(): void
    {
        self::$client->quit();
        parent::tearDown();
    }
    
    protected function Client(): Client
    {
        /**z
         * @var $client Client
         */
        if (!self::$client) {
            self::$client = Client::createFirefoxClient(
              __DIR__.'/../geckodriver',
              null,
              [
                
                'followRedirects' => false,
              ],
              self::HTTP_HEXER_LOCALHOST,
            );
        }
        return self::$client;
    }
    
    /**
     * Limpia la base de datos del otro proyecto hexerAPI
     * @return void
     */
    private function cleanDB(): void
    {
        $cmd = 'php /home/www/hexerAPI/bin/console h:f:l  --env=dev -n  --purge-with-truncate'; # --env=test';
        shell_exec($cmd);
    }
    
    private function cleanCache(): void
    {
        $cmd = 'sudo rm -rf /home/www/hexer/var/cache/* &'; # --env=test';
        shell_exec($cmd);
    }
    
    protected
    function setSelect2(
      Crawler $select,
      string $value,
      Client $client
    ): void {
        // Win10/FF (only!) needs to scroll down :-\
        //SeleniumHelper.scrollTo(driver, select);
        
        // both @ids are constructed in a predictable manner
        $select_id  = $select->getAttribute("id");
        $container1 = WebDriverBy::id("select2-".$select_id."-container");
        $container2 = WebDriverBy::xpath("./parent::span");
        $container  = $client->findElement($container1)
                             ->findElement($container2);
        // open the select container
        $container->click();
        $crawler         = $client->getCrawler();
        $select2_results = $crawler->filter('li.select2-results__option');
        //	$container->sendKeys($value);
        // find one of the results by text
        foreach ($select2_results as $result) {
            /** @var $result \Facebook\WebDriver\Remote\RemoteWebElement */
            $this->echo('Opción:'.$result->getText());
            if ($result->getText() == $value) {
                $result->click();
                break;
            }
        }
//}
    }
    
    /**
     * Muestra un mensaje en pantalla
     * @param string $text
     * @return void
     */
    public function echo(string $text): void
    {
        //	$this->expectOutputString('Hello');
        $out = fopen('php://stdout', 'w');
        fputs($out, $text."\n");
        fclose($out);
        return;
    }
}
