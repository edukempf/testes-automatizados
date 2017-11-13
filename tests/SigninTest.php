<?php

use Facebook\WebDriver\Remote\RemoteWebDriver,
    Facebook\WebDriver\Remote\DesiredCapabilities,
    Facebook\WebDriver\WebDriverBy,
    Facebook\WebDriver\WebDriverSelect,
    Facebook\WebDriver\WebDriverWait,
    Facebook\WebDriver\WebDriverExpectedCondition;

class SigninTest extends \PHPUnit\Framework\TestCase
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

    /**
     * @group ex1
     */
    public function testFazerLoginComUsuarioExistente()
    {
        // Act
        $this->driver->findElement(WebDriverBy::linkText('Sign in'))->click();
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="login"]'))
            ->sendKeys('unicesumar');
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="password"]'))
            ->sendKeys('123456');
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox a'))->click();
        $actual = $this->driver->findElement(WebDriverBy::className('me'))->getText();
        // Assert
        $this->assertContains('Unicesumar', $actual);
    }

    /**
     * @group ex2
     */
    public function testSignupNovoUsuario()
    {
        $login = 'Felipe' . date('YmdHi');
        // Act
        $this->driver->findElement(WebDriverBy::id('signup'))->click();
        $this->driver->findElement(WebDriverBy::cssSelector('#signupbox input[name="name"]'))
            ->sendKeys('Felipe');
        $this->driver->findElement(WebDriverBy::cssSelector('#signupbox input[name="login"]'))
            ->sendKeys($login);
        $this->driver->findElement(WebDriverBy::cssSelector('#signupbox input[name="password"]'))
            ->sendKeys('123456');
        $this->driver->findElement(WebDriverBy::linkText('SAVE'))->click();
        $actual = $this->driver->findElement(WebDriverBy::className('me'))->getText();
        // Assert
        $this->assertContains('Felipe', $actual);
    }

    /**
     * @group ex3
     */
    public function testCriaNovaTarefa()
    {
        // Act
        $this->driver->findElement(WebDriverBy::linkText('Sign in'))->click();
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="login"]'))
            ->sendKeys('unicesumar');
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="password"]'))
            ->sendKeys('123456');
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox a'))->click();
        $this->driver->findElement(WebDriverBy::linkText("LET'S ADD SOME TASKS!"))->click();
        $this->driver->findElement(WebDriverBy::tagName('button'))->click();
        $this->driver->findElement(WebDriverBy::name('title'))->sendKeys('Teste Felipe');
        $this->driver->findElement(WebDriverBy::name('date'))->click();
        $this->driver->findElement(WebDriverBy::xpath("//*[text() = '10']"))->click();
        $this->driver->findElement(WebDriverBy::xpath("//*[text() = 'Ok']"))->click();
        $this->driver->findElement(WebDriverBy::name('time'))->click();
        $this->driver->findElement(WebDriverBy::xpath("//*[text() = 'OK']"))->click();
        $this->driver->findElement(WebDriverBy::name('text'))->sendKeys('Lorem ipsum');
        $this->driver->findElement(WebDriverBy::linkText('SAVE'))->click();
        // Assert
        $this->assertEquals(true, true);
    }

    /**
     * @group ex4
     */
    public function testEsperaErroAoCadastrarNomeVazio()
    {
        // Act
        $this->driver->findElement(WebDriverBy::linkText('Sign in'))->click();
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="login"]'))
            ->sendKeys('unicesumar');
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="password"]'))
            ->sendKeys('123456');
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox a'))->click();
        $this->driver->findElement(WebDriverBy::className('me'))->click();
        $this->driver->findElement(WebDriverBy::name('name'))->clear();
        $this->driver->findElement(WebDriverBy::id('changeAboutYou'))->click();
        $actual = $this->driver->findElement(WebDriverBy::cssSelector('.toast.rounded'))->getText();
        $expected = 'Ask to change your name and do not tell it, does not make sense!';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @group ex5
     */
    public function testAddMoreDataAboutYou()
    {
        // Act
        $this->driver->findElement(WebDriverBy::linkText('Sign in'))->click();
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="login"]'))
            ->sendKeys('unicesumar');
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="password"]'))
            ->sendKeys('123456');
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox a'))->click();
        $this->driver->findElement(WebDriverBy::className('me'))->click();
        $this->driver->findElement(WebDriverBy::xpath("//a[@href = '#moredata']"))->click();
        $this->driver->findElement(WebDriverBy::xpath("//button[text() = '+ Add more data']"))->click();
        $field_type = $this->driver->findElement(WebDriverBy::name('type'));
        $combo_type = new WebDriverSelect($field_type);
        $combo_type->selectByValue('phone');
        $this->driver->findElement(WebDriverBy::name('contact'))->sendKeys('9999999');
        $this->driver->findElement(WebDriverBy::linkText('SAVE'))->click();
        $wait = new WebDriverWait($this->driver, 8, 1000);
        $wait->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(
                WebDriverBy::cssSelector('.toast.rounded')
            )
        );
        $elements = $this->driver->findElement(WebDriverBy::id('moredata'))
            ->findElements(WebDriverBy::tagName('a'));
        $element = count($elements);
        $this->driver->findElement(WebDriverBy::xpath("//div[@id='moredata']/div/ul/li[$element]/a"))->click();
        $alerta = $this->driver->switchTo()->alert();
        $alerta->accept();
        $actual = $this->driver->findElement(WebDriverBy::cssSelector('.toast.rounded'))->getText();
        $expected = 'Rest in peace, dear phone!';
        $this->driver->executeScript('window.scroll(0, 0)');
        $this->driver->takeScreenshot('evidences/screebshot.jpg');
        $this->assertEquals($expected, $actual);
    }

    public function tearDown()
    {
        $this->driver->quit();
    }

}