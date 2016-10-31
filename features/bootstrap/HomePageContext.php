<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Gherkin\Node\TableNode;

class HomePageContext extends PageObjectContext
{
    public function __construct()
    {
    }

    /**
     * @Given /^the browser is set to size$/
     */
    public function resizeBrowser()
    {
        $this->getPage('Browser')->setBrowserToSize('PhantomJS', 1440, 900);
    }

    /**
     * @Then I fill in the eligibility form with
     */
    public function iFillInTheEligibilityFormWith(TableNode $table)
    {
        $homePage = $this->getPage('HomePage');
        $homePage->fillInAutocompleteForm($table, 'eligibilityPostCode', '.ui-menu-item', 'NPA');
        $homePage->fillInAutocompleteForm($table, 'eligibilityStreetName', '.ui-menu-item', 'Street');
        $homePage->fillInAutocompleteForm($table, 'eligibilityStreetNumber', '.ui-menu-item-wrapper', 'Number');
    }

    /**
     * @When I click menu icon
     */
    public function iClickMenuIcon()
    {
        $this->getPage('HomePage')->find("css", ".burger-icon")->click();
    }
}