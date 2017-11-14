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

use pages\AboutYouPage;

class SecretPage implements PageInterface
{
    /**
     * @var RemoteWebDriver
     */
    private $driver;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function clickHi() {
        $this->driver->findElement(WebDriverBy::className('me'))->click();
        return (new AboutYouPage($this->driver));
    }
}