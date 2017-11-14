<?php
/**
 * Created by PhpStorm.
 * User: UniCesumar
 * Date: 13/11/2017
 * Time: 19:05
 */

namespace pages;

use Facebook\WebDriver\Remote\RemoteWebDriver,
    Facebook\WebDriver\WebDriverBy,
    Facebook\WebDriver\WebDriverSelect,
    Facebook\WebDriver\WebDriverWait,
    Facebook\WebDriver\WebDriverExpectedCondition;



class AboutYouPage implements PageInterface
{
    /**
     * @var RemoteWebDriver
     */
     private $driver;

     public function __construct($driver)
     {
         $this->driver = $driver;
     }

     public function clickMoreDataAboutYou(){
         $this->driver->findElement(WebDriverBy::xpath("//a[@href = '#moredata']"))->click();
         return $this;
     }

     public function clickAddMoreData(){
         $this->driver->findElement(WebDriverBy::xpath("//button[text() = '+ Add more data']"))->click();
         return $this;
     }

     public function setType($type) {
         $field_type = $this->driver->findElement(WebDriverBy::name('type'));
         $combo_type = new WebDriverSelect($field_type);
         $combo_type->selectByValue($type);
         return $this;
     }

     public function writeContact($value) {
         $this->driver->findElement(WebDriverBy::name('contact'))->sendKeys($value);
         return $this;
     }

     public function clickButtonSave() {
         $this->driver->findElement(WebDriverBy::linkText('SAVE'))->click();
         return $this;
     }

     public function wait($time){
         $wait = new WebDriverWait($this->driver, $time, 1000);
         $wait->until(
             WebDriverExpectedCondition::invisibilityOfElementLocated(
                 WebDriverBy::cssSelector('.toast.rounded')
             )
         );
         return $this;
     }

     public function getLastElement(){
         $elements = $this->driver->findElement(WebDriverBy::id('moredata'))
             ->findElements(WebDriverBy::tagName('a'));
         $element = count($elements);
         $this->driver->findElement(WebDriverBy::xpath("//div[@id='moredata']/div/ul/li[$element]/a"))->click();
         return $this;

     }

     public function deleteLastElement(){
         $alerta = $this->driver->switchTo()->alert();
         $alerta->accept();
         return $this;
     }

     public function getMessageDelete(){

         return $this->driver->findElement(WebDriverBy::cssSelector('.toast.rounded'))->getText();
     }

}