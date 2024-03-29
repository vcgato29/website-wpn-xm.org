<?php

class GetTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
       //
    }

    /**
     * @param $url
     */
    public function setGetRequest($url)
    {
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $array);
        $_GET = $array;
        require_once dirname(__DIR__) . '/get.php';

        $request  = new Request();
        $this->response = new Response();
        $registry = new Registry();
        require_once dirname(__DIR__) . '/stats/Database.php';
        $database = new Database();

        $component = new Component($request, $this->response, $registry, $database);
        $component->redirectTo();
    }

    public function testRequestLatestVersion()
    {
        $this->setGetRequest('http://wpn-xm.org/get.php?s=nginx');

        $this->assertContains('http://nginx.org/download/nginx-', $this->response->url);
        $this->assertEquals(1, preg_match('#nginx-(\d+.\d+.\d+).zip#i', $this->response->url));
    }

    public function testRequestSpecificVersion()
    {
        $this->setGetRequest('http://wpn-xm.org/get.php?s=nginx&v=1.0.0');

        $this->assertEquals(
            'http://www.nginx.org/download/nginx-1.0.0.zip',
            $this->response->url
        );
    }

    public function testRequest_PHPExtension_Phalcon_PHPVersion_MajorMinor_54()
    {
        $this->setGetRequest('http://wpn-xm.org/get.php?s=phpext_phalcon&p=5.4');

        $this->assertRegExp(
            '#https://static.phalconphp.com/www/files/phalcon_x86_VC9_php(\d+\.\d+\.\d+)_(\d+\.\d+\.\d+(.RC\d+)?)_nts.zip#i',
            $this->response->url
        );
    }

    public function testRequest_PHPExtension_Phalcon_PHPVersion_MajorMinorPatch_540()
    {
        $this->setGetRequest('http://wpn-xm.org/get.php?s=phpext_phalcon&p=5.4.0');

        $this->assertRegExp(
            '#https://static.phalconphp.com/www/files/phalcon_x86_VC9_php(\d+\.\d+\.\d+)_(\d+\.\d+\.\d+(.RC\d+)?)_nts.zip#i',
            $this->response->url
        );
    }

    public function testRequest_PHPExtension_Phalcon_PHPVersion_MajorMinor_55()
    {
        $this->setGetRequest('http://wpn-xm.org/get.php?s=phpext_phalcon&p=5.5');

        $this->assertRegExp(
            '#https://static.phalconphp.com/www/files/phalcon_x86_vc11_php(\d+\.\d+\.\d+)_(\d+\.\d+\.\d+(.RC\d+)?)_nts.zip#i',
            $this->response->url
        );
    }
    
    public function testRequest_PHPExtension_Wincache_LatestVersion()
    {        
        // we are testing because of the Major.Minor.Patch.Whatever version number
        // this is a request for the latest version with "default PHP version" and "default bitsize"       
        $this->setGetRequest('http://wpn-xm.org/get.php?s=phpext_wincache');

        $this->assertEquals(
            'http://windows.php.net/downloads/pecl/releases/wincache/1.3.7.9/php_wincache-1.3.7.9-5.5-nts-VC11-x86.zip',
            $this->response->url
        );
    }
    
    public function testRequest_PHPExtension_Wincache_LatestVersion_MajorMinor_56_x64()
    {             
        $this->setGetRequest('http://wpn-xm.org/get.php?s=phpext_wincache&p=5.6&bitsize=x64');

        $this->assertEquals(
            'http://windows.php.net/downloads/pecl/releases/wincache/1.3.7.9/php_wincache-1.3.7.9-5.6-nts-VC11-x64.zip',
            $this->response->url
        );
    }
    
    public function testRequest_PHPQA_SpecificVersion()
    {
        // request for "PHP QA 7.0.1RC1" with default bitsize "x86"
        $this->setGetRequest('http://wpn-xm.org/get.php?s=php-qa&v=7.0.1RC1');
       
        $this->assertEquals(
            'http://windows.php.net/downloads/qa/archives/php-7.0.1RC1-nts-Win32-VC14-x86.zip',
            $this->response->url
        );
    }
    
    public function testRequest_PHPQA_LatestVersion_MajorMinorRange_56()
    {
        // request for "latest version" of "PHP 5.6.*" (range) with default bitsize "x86"
        $this->setGetRequest('http://wpn-xm.org/get.php?s=php-qa&p=5.6');
       
        $this->assertEquals(
            'http://windows.php.net/downloads/qa/archives/php-5.6.11RC1-nts-Win32-VC11-x86.zip',
            $this->response->url
        );
    }
    
    public function testRequest_PHPExtension_Trader_LatestVersion_MajorMinor_54()
    {
        $this->setGetRequest('http://wpn-xm.org/get.php?s=phpext_trader&p=5.4');

        $this->assertEquals(
            'http://windows.php.net/downloads/pecl/releases/trader/0.4.0/php_trader-0.4.0-5.4-nts-VC9-x86.zip',
            $this->response->url
        );
    }    

    public function testRequest_PHP_LatestVersion_MajorMinor_54()
    {
        $this->setGetRequest('http://wpn-xm.org/get.php?s=php&p=5.4');

        $this->assertContains("http://windows.php.net/downloads/releases/", $this->response->url);
        $this->assertContains("5.4", $this->response->url);
    }

    public function testRequest_PHP_LatestVersion_MajorMinor_56()
    {
        $this->setGetRequest('http://wpn-xm.org/get.php?s=php&p=5.6');

        $this->assertContains("http://windows.php.net/downloads/releases/", $this->response->url);
        $this->assertContains("5.6", $this->response->url);
    }
    
    public function testRequest_PHP_DefaultVersion_Major_7()
    {
        // p=7 is invalid; the default PHP version is set instead
        $this->setGetRequest('http://wpn-xm.org/get.php?s=php&p=7');
        
        $this->assertContains("http://windows.php.net/downloads/", $this->response->url);        
        $this->assertEquals(1, preg_match('#php-(\d+.\d+.\d+)-nts-#i', $this->response->url));
        $this->assertContains("5.", $this->response->url);        
    }
    
    public function testRequest_PHPExtension_XDebug_DefaultVersion_Major_7()
    {
        // p=7 is invalid; the default PHP version is set instead
        $this->setGetRequest('http://wpn-xm.org/get.php?s=phpext_xdebug&p=7');

        var_dump($this->response->url);
        $this->assertContains("http://windows.php.net/downloads/pecl/releases/xdebug/", $this->response->url);        
        $this->assertEquals(1, preg_match('#php_xdebug-(.*)-5.5-nts-VC11-x86.zip#i', $this->response->url));
        $this->assertContains("5.5", $this->response->url); 
               
    }
}
