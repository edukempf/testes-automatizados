<?php

use Facebook\WebDriver\Remote\RemoteWebDriver,
    Facebook\WebDriver\Remote\DesiredCapabilities,
    Facebook\WebDriver\WebDriverBy,
    Facebook\WebDriver\WebDriverSelect,
    Facebook\WebDriver\WebDriverWait,
    Facebook\WebDriver\WebDriverExpectedCondition;

class ComponentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RemoteWebDriver
     */
    private $driver;

    public function setUp()
    {
        $this->driver = RemoteWebDriver::create("http://localhost:4444", DesiredCapabilities::chrome());
        $this->driver->manage()->window()->maximize();
        $this->driver->manage()->timeouts()->implicitlyWait(5);
        $this->driver->get('http://www.juliodelima.com.br/taskit');
    }


    public function escreveTexto($campo, $texto, $tipo)
    {
        switch ($tipo)
        {
            case 'id':
                $this->driver->findElement(WebDriverBy::id($campo))->sendKeys($texto);
                break;
            case 'nomeClasse':
                $this->driver->findElement(WebDriverBy::className($campo))->sendKeys($texto);
                break;
            case 'seletorCss':
                $this->driver->findElement(WebDriverBy::cssSelector($campo))->sendKeys($texto);
                break;
        }
    }

    public function limparTexto($campo, $tipo)
    {
        switch ($tipo)
        {
            case 'id':
                $this->driver->findElement(WebDriverBy::id($campo))->clear();
                break;
            case 'nomeClasse':
                $this->driver->findElement(WebDriverBy::className($campo))->clear();
                break;
            case 'seletorCss':
                $this->driver->findElement(WebDriverBy::cssSelector($campo))->clear();
                break;
        }
    }

    public function click($texto,$tipo)
    {
        switch ($tipo)
        {
            case 'id':
                $this->driver->findElement(WebDriverBy::id($texto))->click();
                break;
            case 'nomeClasse':
                $this->driver->findElement(WebDriverBy::className($texto))->click();
                break;
            case 'seletorCss':
                $this->driver->findElement(WebDriverBy::cssSelector($texto))->click();
                break;
        }

    }

    public function lerTexto($texto, $tipo)
    {
        switch ($tipo)
        {
            case 'id':
                return $this->driver->findElement(WebDriverBy::id($texto))->getText();
            case 'nomeClasse':
                return $this->driver->findElement(WebDriverBy::className($texto))->getText();
            case 'seletorCss':
                return $this->driver->findElement(WebDriverBy::cssSelector($texto))->getText();
        }
    }

    /**
     * @group teste inserção de carinha
     */
    public function testFazerLoginComUsuarioExistente()
    {
        $this->click('signup','id');
        $this->escreveTexto('#signupbox input[name="name"]','Teste aula 02', 'seletorCss');
        $time = date_timestamp_get(new DateTime());
        $this->escreveTexto('#signupbox input[name="login"]','unicesumar'.$time, 'seletorCss');
        $this->escreveTexto('#signupbox input[name="password"]', '123', 'seletorCss');
        $this->click('#signupbox a', 'seletorCss');

        $this->click('me', 'nomeClasse');

        $this->limparTexto('#aboutyou input[name="name"]', 'seletorCss');
        $this->escreveTexto('#aboutyou input[name="name"]','Teste aula 02 alterado', 'seletorCss');

        $this->click('changeAboutYou','id');

        $actual = $this->lerTexto('me', 'nomeClasse');
        $this->assertContains('Teste aula 02 alterado', $actual);
    }

    public function tearDown()
    {
        $this->driver->quit();
    }
}