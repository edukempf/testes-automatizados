<?php
/**
 * Created by PhpStorm.
 * User: UniCesumar
 * Date: 13/11/2017
 * Time: 19:05
 */
namespace pages;

use Facebook\WebDriver\Remote\RemoteWebDriver,
    Facebook\WebDriver\WebDriverBy;


class LoginPage implements PageInterface
{
    /**
     * @var RemoteWebDriver
     */
    private $driver;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function clickSignin(){
        $this->driver->findElement(WebDriverBy::linkText('Sign in'))->click();
        return $this;
    }

    public function writeLogin($login)
    {
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="login"]'))
            ->sendKeys($login);
        return $this;
    }

    public function writePassword($password)
    {
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox input[name="password"]'))
            ->sendKeys($password);
        return $this;
    }

    public function clickButtonSignin()
    {
        $this->driver->findElement(WebDriverBy::cssSelector('#signinbox a'))->click();
        return (new SecretPage($this->driver));
    }

}