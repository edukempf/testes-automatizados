<?php
/**
 * Created by PhpStorm.
 * User: UniCesumar
 * Date: 13/11/2017
 * Time: 19:23
 */


use Facebook\WebDriver\Remote\RemoteWebDriver,
    Facebook\WebDriver\Remote\DesiredCapabilities,
    pages\LoginPage;


class TestPages extends \PHPUnit\Framework\TestCase
{
    private $driver;
    private $sessionId;

    public function setUp()
    {
        //$this->driver = RemoteWebDriver::create(
        //    "http://juliocesardelima2:wUCEnr7XjcXSEfLZsSvp@hub-cloud.browserstack.com/wd/hub",
        //    array("browser" => "Chrome", "browser_version" => "62.0", "os" => "Windows", "os_version" => "10", "resolution" => "1280x800")
        //);

        $this->driver = RemoteWebDriver::create("http://localhost:4444", DesiredCapabilities::chrome());

        $this->driver->manage()->window()->maximize();
        $this->driver->manage()->timeouts()->implicitlyWait(10);
        $this->driver->get('http://www.juliodelima.com.br/taskit');
        $this->sessionId = $this->driver->getSessionID();
    }

    public static function dataDoTestRemove(){
        $file = file_get_contents("C:\\xampp\\htdocs\\aula02\\tests\\pages\\bd.json");
        $dados = json_decode($file,true);
        return $dados;

    }

    /**
     * @dataProvider dataDoTestRemove
     */
    public function testRemove($login, $password, $type, $contact, $time){
        $actual = (new LoginPage($this->driver))
            ->clickSignin()
            ->writeLogin($login)
            ->writePassword($password)
            ->clickButtonSignin()
            ->clickHi()
            ->clickMoreDataAboutYou()
            ->clickAddMoreData()
            ->setType($type)
            ->writeContact($contact)
            ->clickButtonSave()
            ->wait($time)
            ->getLastElement()
            ->deleteLastElement()
            ->getMessageDelete();

        $expected = 'Rest in peace, dear phone!';
        $this->assertEquals($expected, $actual);
    }

    public function tearDown()
    {

        if ($this->getStatusMessage() != "") {
            file_get_contents("http://juliocesardelima2:wUCEnr7XjcXSEfLZsSvp@www.browserstack.com/automate/sessions/$this->sessionId.json",
                false,
                stream_context_create(array('http' =>
                    array('method' => 'PUT',
                        'header' => 'Content-type: application/json',
                        'content' => '{"status":"failed","reason":"$this->getStatusMessage()"}'))));
    }
        $this->driver->quit();
    }
}