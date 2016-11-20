<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Gherkin\Node\TableNode;

class HomePageContext extends PageObjectContext
{
    public function __construct(){}

    public function __call($method, $parameters)
    {
        $page = $this->getPage('HomePage');
        if (method_exists($page, $method)) {
            return call_user_func_array(array($page, $method), $parameters);
        }
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
        $this->resetFiberEligibility('#fiberEligOk');
        $this->fillInAutocompleteForm($table, 'eligibilityPostCode', '#ui-id', 'NPA');
        $this->enableElementId('eligibilityStreetName');
        $this->fillInAutocompleteForm($table, 'eligibilityStreetName', '#ui-id', 'Street');
        $this->enableElementId('eligibilityStreetNumber');
        $this->fillInAutocompleteForm($table, 'eligibilityStreetNumber', '.ui-menu-item-wrapper', 'Number');
    }

    /**
     * @When I click menu icon
     */
    public function iClickMenuIcon()
    {
        $this->find("css", ".burger-icon")->click();
    }

    /**
     * @Then I fill in the eligibility billing form with
     */
    public function iFillInTheEligibilityBillingForm(TableNode $table)
    {
        $this->fillInAutocompleteForm($table, 'billing_postcode', '#ui-id', 'NPA');
        $this->enableElementId('billing_city');
        $this->fillInAutocompleteForm($table, 'billing_city', '#ui-id', 'City');
        $this->enableElementId('billing_street1');
        $this->fillInAutocompleteForm($table, 'billing_street1', '#ui-id', 'Street');
        $this->enableElementId('billing_street2');
        $this->fillInAutocompleteForm($table, 'billing_street2', '#ui-id', 'Number');
    }
}