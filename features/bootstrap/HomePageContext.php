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

    protected $elements = array(
        'Autocomplete element' => '#ui-id',
        'Autocomplete wrapper' => array('css' => '.ui-menu-item-wrapper')
    );

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
        $this->resetFiberEligibility();
        $this->fillInAutocompleteForm($table, 'eligibilityPostCode', 'Autocomplete element', 'NPA');
        $this->enableElementId('eligibilityStreetName');
        $this->fillInAutocompleteForm($table, 'eligibilityStreetName', 'Autocomplete element', 'Street');
        $this->enableElementId('eligibilityStreetNumber');
        $this->fillInAutocompleteForm($table, 'eligibilityStreetNumber', 'Autocomplete wrapper', 'Number');
    }

    /**
     * @When I click menu icon
     */
    public function iClickMenuIcon()
    {
        $this->clickMenu();
    }

    /**
     * @Then I fill in the eligibility billing form with
     */
    public function iFillInTheEligibilityBillingForm(TableNode $table)
    {
        $this->fillInAutocompleteForm($table, 'billing_postcode', 'Autocomplete element', 'NPA');
        $this->enableElementId('billing_city');
        $this->fillInAutocompleteForm($table, 'billing_city', 'Autocomplete element', 'City');
        $this->enableElementId('billing_street1');
        $this->fillInAutocompleteForm($table, 'billing_street1', 'Autocomplete element', 'Street');
        $this->enableElementId('billing_street2');
        $this->fillInAutocompleteForm($table, 'billing_street2', 'Autocomplete element', 'Number');
    }
}