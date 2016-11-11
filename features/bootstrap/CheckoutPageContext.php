<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Gherkin\Node\TableNode;

class CheckoutPageContext extends PageObjectContext
{

    public function __construct()
    {
    }

    /**
     * @Given /^I fill in the content form with random credentials$/
     */
    public function iFillInCheckoutContentFormWith(TableNode $table)
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->fillInCheckoutTitleDetails($table);
        $checkoutPage->fillInCheckoutPersonalDetails();
        $checkoutPage->fillInCheckoutEmailAddress();
        $checkoutPage->fillInCheckoutPhoneNumber();
    }

    /**
     * @Given /^I fill in the mobile content form with random credentials$/
     */
    public function iFillInCheckoutMobileContentFormWith(TableNode $table)
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->fillInCheckoutTitleDetails($table);
        $checkoutPage->fillInCheckoutMobilePersonalDetails();
    }


    /**
     * @Then /^I enter date in the calendar$/
     */
    public function iEnterDateInTheCalendar()
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->fillInDateInTheCalendar();
    }

    /**
     * @Then /^I fill in my birthday form$/
     */
    public function iFillInMyBirthdayForm()
    {
        print 'TO DO';
    }
}