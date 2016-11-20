<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Gherkin\Node\TableNode;
use Assert\Assertion;

class CheckoutPageContext extends PageObjectContext
{

    private $params = array();

    public function __construct($parameters)
    {
        $this->params = $parameters;
        date_default_timezone_set('Europe/Zurich');
    }

    public function __call($method, $parameters)
    {
        $page = $this->getPage('CheckoutPage');
        if (method_exists($page, $method)) {
            return call_user_func_array(array($page, $method), $parameters);
        }
    }

    /**
     * @Given /^I fill in the content form with random credentials$/
     */
    public function iFillInCheckoutContentFormWith(TableNode $table)
    {
        $this->fillInCheckoutTitleDetails($table);
        $this->fillInCheckoutPersonalDetails();
        $this->fillInCheckoutEmailAddress();
        $this->fillInCheckoutPhoneNumber();
    }

    /**
     * @Given /^I fill in the mobile content form with random credentials$/
     */
    public function iFillInCheckoutMobileContentFormWith(TableNode $table)
    {
        $this->fillInCheckoutTitleDetails($table);
        $this->fillInCheckoutMobilePersonalDetails();
    }

    /**
     * @Given /^I fill in minor user form with random credentials$/
     */
    public function iFillInMinorUserFormWith(TableNode $table)
    {
        $this->fillInCheckoutTitleDetails($table);
        $this->fillInMinorUserDetails();
    }


    /**
     * @Then /^I enter date in the calendar$/
     */
    public function iEnterDateInTheCalendar()
    {
        $this->fillInDateInTheCalendar();
    }

    /**
     * @Then /^I fill in my birthday form$/
     * @Then /^I fill in my birthday form "([^"]*)"$/
     */
    public function iFillInBirthdayForm($birthday)
    {
        if ($this->getAge($birthday) >= 18) {
            $this->fillInFormField('custom_step2_dob_day', $this->getDay($birthday));
            $this->fillInFormField('custom_step2_dob_month', $this->getMonth($birthday));
            $this->fillInFormField('custom_step2_dob_year', $this->getYear($birthday));
        } else if ($this->getAge($birthday) < 18 && $this->getAge($birthday) >= 12) {
            $this->fillInFormField('custom_step2_dob_underage_day', $this->getDay($birthday));
            $this->fillInFormField('custom_step2_dob_underage_month', $this->getMonth($birthday));
            $this->fillInFormField('custom_step2_dob_underage_year', $this->getYear($birthday));
        } else {
            throw new \Exception ('Age requirements are not correct');
        }
    }

    private function formatDate($date)
    {
        return str_replace(".", " ", $date);
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
        $birthday = explode(".", $birthday);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthday[0], $birthday[1], $birthday[2]))) > date("md")
            ? ((date("Y") - $birthday[2]) - 1)
            : (date("Y") - $birthday[2]));
        return $age;
    }

    public function setLocalTimeZone() {
        $timeZone = date_default_timezone_get();
        if ($timeZone != ini_get('date.timezone')) {
            return date_default_timezone_set($timeZone);
        }
    }

    /**
     * @Given /^I fill in the billing content form with random credentials "([^"]*)"$/
     * @When /^I fill in the billing content form with random credentials$/
     */
    public function iFillInTheBillingContentFormWithRandomCredentials($email = null)
    {
        if ($email == null) {
            $this->fillInBillingEmailAddress();
        } else {
            $this->fillField('billing_email', $email);
            $this->fillField('billing_email_confirm', $email);
        }
        $this->fillInBillingPhoneNumber();
    }

    /**
     * @Given /^I select "([^"]*)" from the list$/
     */
    public function iSelectNationalityFromTheList($nationality)
    {
        $this->selectNationality($nationality);
    }

    /**
     * @Then /^I fill in password fields "([^"]*)"$/
     */
    public function iFillInPasswordFields($password)
    {
        $this->fillInPassword($password);
        $this->fillInPasswordConfirmation($password);
    }

    /**
     * @Then /^I select identity card "([^"]*)" from the list$/
     */
    public function iSelectIdentityCardFromTheList($cardType)
    {
        $this->selectIdentityCard($cardType);
    }

    /**
     * @Given /^I fill in random identity card number according to the "([^"]*)" and type of "([^"]*)"$/
     */
    public function iFillInIdentityCardNumberAccordingToTheAndTypeOf($nationality, $cardType)
    {
        if (in_array($nationality, $this->params['switzerland']) && (in_array($cardType, $this->params['passport']))) {
            $this->fillInPassportNumber();
        } else if (in_array($nationality, $this->params['switzerland']) && (in_array($cardType, $this->params['cardId']))) {
            $this->fillInIdCardNumber();
        } else {
            $this->fillInPieceLegitimation();
        }
    }

    /**
     * @Then /^I accept Wingo conditions$/
     */
    public function iAcceptWingoConditions()
    {
        $this->acceptWingoCheckoutConditions();
    }

    /**
     * @Then /^I press button Continue$/
     */
    public function iPressButtonContinue()
    {
        $this->clickButtonContinue();
    }

    /**
     * @Given /^the "([^"]*)" is present on the acknowledgment page$/
     */
    public function theEmailIsPresentOnTheAcknowledgmentPage($email)
    {
        $this->checkConfirmationEmail($email);
    }

    /**
     * @Then /^I am varifying previously filled in data$/
     */
    public function iAmVarifyingPreviouslyFilledInData(TableNode $table)
    {
        $this->checkCheckoutOperatorName($table);
        $this->checkCheckoutContractType($table);
        $this->checkCheckoutTerm($table);
        $this->checkCheckoutTitle($table);
        $this->checkCheckoutBirthDay($table);
        $this->checkCheckoutNationality($table);
        $this->checkCheckoutIdCard($table);
    }
}