<?php

use Behat\Behat\Context\Context,
    Facebook\WebDriver\Remote\RemoteWebDriver,
    Facebook\WebDriver\Remote\DesiredCapabilities,
    Facebook\WebDriver\WebDriverBy,
    PHPUnit\Framework\Assert,
    Behat\Behat\Tester\Exception\PendingException,
    Behat\Gherkin\Node\TableNode;

use Facebook\WebDriver\WebDriverSelect,
    Facebook\WebDriver\WebDriverWait,
    Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $driver;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {

    }

    /** @BeforeScenario */
    public function before($event)
    {
        $this->driver = RemoteWebDriver::create("http://localhost:4444", DesiredCapabilities::chrome());
        $this->driver->manage()->window()->maximize();
        $this->driver->manage()->timeouts()->implicitlyWait(5);
        $this->driver->get('http://www.juliodelima.com.br/taskit');
    }

    /**
     * @Given I am logged in:
     */
    public function iAmLoggedIn(TableNode $table)
    {
        $dados = $table->getHash()[0];
        $login = $dados['login'];
        $password = $dados['password'];
        $name = $dados['name'];

        $this->driver->findElement(WebDriverBy::linkText('Sign in'))->click();
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="login"]'))
            ->sendKeys($login);
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="password"]'))
            ->sendKeys($password);
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox a'))->click();
        $actual = $this->driver->findElement(WebDriverBy::className('me'))->getText();

        Assert::assertContains($name, $actual);
    }

    /**
     * @Given I am seeing my contact data
     */
    public function iAmSeeingMyContactData()
    {
        $this->driver->findElement(WebDriverBy::className('me'))->click();
        $this->driver->findElement(WebDriverBy::xpath("//a[@href = '#moredata']"))->click();
        $actual = $this->driver->findElement(WebDriverBy::xpath("//button[text() = '+ Add more data']"))->getText();
        Assert::assertContains('+ ADD MORE DATA', $actual);
    }

    /**
     * @When I add a new phone
     */
    public function iAddANewPhone()
    {
        $this->driver->findElement(WebDriverBy::xpath("//button[text() = '+ Add more data']"))->click();
        $field_type = $this->driver->findElement(WebDriverBy::name('type'));
        $combo_type = new WebDriverSelect($field_type);
        $combo_type->selectByValue('phone');
        $this->driver->findElement(WebDriverBy::name('contact'))->sendKeys('9999999');
        $this->driver->findElement(WebDriverBy::linkText('SAVE'))->click();
    }


    /**
     * @When I add a new email
     */
    public function iAddANewEmail()
    {
        $this->driver->findElement(WebDriverBy::xpath("//button[text() = '+ Add more data']"))->click();
        $field_type = $this->driver->findElement(WebDriverBy::name('type'));
        $combo_type = new WebDriverSelect($field_type);
        $combo_type->selectByValue('email');
        $this->driver->findElement(WebDriverBy::name('contact'))->sendKeys('dsada@ddas.sa');
        $this->driver->findElement(WebDriverBy::linkText('SAVE'))->click();
    }

    /**
     * @Then I see a confirmation message
     */
    public function iSeeAConfirmationMessage()
    {
        $actual = $this->driver->findElement(WebDriverBy::cssSelector('.toast.rounded'))->getText();
        $expected = 'Your contact has been added!';
        Assert::assertContains($expected, $actual);
    }

    /** @AfterScenario */
    public function after($event)
    {
        $this->driver->quit();
    }
}
