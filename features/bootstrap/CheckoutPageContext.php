<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Gherkin\Node\TableNode;

class CheckoutPageContext extends PageObjectContext
{

    private $params = array();

    public function __construct($parameters)
    {
        $this->params = $parameters;
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
     * @Then /^I fill in my birthday form "([^"]*)"$/
     */
    public function iFillInBirthdayForm($birthday)
    {
        #if ($birthday == date("d-m-Y"))
        $checkoutPage = $this->getPage('CheckoutPage');
        $this->getAge($birthday);
        $checkoutPage->fillInFormField('custom_step2_dob_day', $this->getDay($birthday));
        $checkoutPage->fillInFormField('custom_step2_dob_month', $this->getMonth($birthday));
        $checkoutPage->fillInFormField('custom_step2_dob_year', $this->getYear($birthday));
    }

    private function formatDate($date)
    {
        return str_replace("-", " ", $date);
    }

    public function getDay($data)
    {
        $day = $this->formatDate($data);
        return substr($day, 0, 2);
    }

    public function getMonth($data)
    {
        $month = $this->formatDate($data);
        return substr($month, 2, 3);
    }

    public function getYear($data)
    {
        $year = $this->formatDate($data);
        return substr($year, 6, 10);
    }

    public function getAge($birthday)
    {
        date_default_timezone_set("Europe/Zurich");
        $birthday = explode("-", $birthday);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthday[0], $birthday[1], $birthday[2]))) > date("md")
            ? ((date("Y") - $birthday[2]) - 1)
            : (date("Y") - $birthday[2]));
        return $age;
    }

    /**
     * @Given /^I fill in the billing content form with random credentials$/
     */
    public function iFillInTheBillingContentFormWithRandomCredentials()
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->fillInBillingEmailAddress();
        $checkoutPage->fillInBillingPhoneNumber();
    }

    /**
     * @Then I fill in the eligibility billing form with
     */
    public function iFillInTheEligibilityBillingForm(TableNode $table)
    {
        $homePage = $this->getPage('HomePage');
        $homePage->fillInAutocompleteForm($table, 'billing_postcode', '#ui-id', 'NPA');
        $homePage->enableElementId('billing_city');
        $homePage->fillInAutocompleteForm($table, 'billing_city', '#ui-id', 'City');
        $homePage->enableElementId('billing_street1');
        $homePage->fillInAutocompleteForm($table, 'billing_street1', '#ui-id', 'Street');
        $homePage->enableElementId('billing_street2');
        $homePage->fillInAutocompleteForm($table, 'billing_street2', '#ui-id', 'Number');
    }

    /**
     * @Given /^I select "([^"]*)" from the list$/
     */
    public function iSelectNationalityFromTheList($nationality)
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->selectNationality($nationality);
    }

    /**
     * @Then /^I fill in password fields "([^"]*)"$/
     */
    public function iFillInPasswordFields($password)
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->fillInPassword($password);
        $checkoutPage->fillInPasswordConfirmation($password);
    }

    /**
     * @Then /^I select identity card "([^"]*)" from the list$/
     */
    public function iSelectIdentityCardFromTheList($cardType)
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->selectIdentityCard($cardType);
    }

    /**
     * @Given /^I fill in identity card number "([^"]*)" according to the "([^"]*)" and type of "([^"]*)"$/
     */
    public function iFillInIdentityCardNumberAccordingToTheAndTypeOf($number, $nationality, $cardType)
    {
        $checkoutPage = $this->getPage('CheckoutPage');

        if (in_array($nationality, $this->params['switzerland']) && (in_array($cardType, $this->params['passport']))) {
            $checkoutPage->fillInPassportNumber($number);
        } else if (in_array($nationality, $this->params['switzerland']) && (in_array($cardType, $this->params['cardId']))) {
            $checkoutPage->fillInIdCardNumber($number);
        } else {
            $checkoutPage->fillInPieceLegitimation($number);
        }
    }

    /**
     * @Then /^I accept Wingo conditions$/
     */
    public function iAcceptWingoConditions()
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->acceptWingoCheckoutConditions();
    }

    /**
     * @Then /^I press button Continue$/
     */
    public function iPressButtonContinue()
    {
        $checkoutPage = $this->getPage('CheckoutPage');
        $checkoutPage->clickButtonContinue();
    }
}