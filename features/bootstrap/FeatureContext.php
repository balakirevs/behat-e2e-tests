<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Exception\ResponseTextException;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Exception\ElementNotFoundException;
use MinkFieldRandomizer\Context\FilterContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
   use FilterContext;
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

    /**
     * @Given /^the browser is set to size$/
     */
    public function setBrowserToSize()
    {
        if ($this->getBrowserName() == 'PhantomJS') {
            $this->getSession()->resizeWindow(1440, 900);
        } else {
            $this->getSession()->maximizeWindow();
        }
    }

    /*
     * Get browser name
     */
    public function getBrowserName()
    {
        $driver = $this->getSession()->getDriver();
        $userAgent = $driver->evaluateScript('return navigator.userAgent');
        $provider = $driver->evaluateScript('return navigator.vendor');
        $browser = null;
        if (preg_match('/google/i', $provider)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif ((strpos('Safari', $userAgent) !== false)) {
            $browser = 'Safari';
        } elseif (preg_match('/MSIE/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/phantomjs/i', $userAgent)) {
            $browser = 'PhantomJS';
        } else {
            print 'Unknown Browser ' . $userAgent . $provider;
        }
        return $browser;
    }

    /**
     * @When I click menu icon
     */
    public function iClickMenuIcon()
    {
        $this->getSession()->getPage()->find("css", ".burger-icon")->click();
    }

    /**
     * @Then navigation bar is displayed
     */
    public function navigationBarIsDisplayed()
    {
        $this->getSession()->getPage()->find('css', '.navbar-wrapper')->isVisible();
    }

    /**
     * @When I click :arg1 link
     */
    public function iClickLink($link)
    {
        $this->getSession()->getPage()->clickLink($link);
    }

    /**
     * @Given I should be redirected to :arg1
     */
    public function iShouldBeRedirectedTo($url)
    {
        $this->getSession()->wait(1000);
        $this->assertPageAddress($url);
    }

    /**
     * @Then I should see eligibility form container
     */
    public function iShouldSeeEligibilityFormContainer()
    {
        $this->assertSession()->elementExists('css', '#eligibilityFormContainer');
    }

    /**
     * @When I click button of a :arg1 product
     */
    public function iClickButtonOfAProduct($colour)
    {
        $element = $this->getSession()->getPage()->find('css', ".price.$colour-bkg-30 > a");
        $element->click();
    }

    /**
     * @Then I fill in the eligibility form with
     */
    public function iFillInTheEligibilityFormWith(TableNode $table)
    {
        $browser = $this->getBrowserName();
        if ($browser == 'Firefox') {
            $this->autocompleteForm($table, 'eligibilityPostCode', '#ui-id-1', 'NPA');
            $this->autocompleteForm($table, 'eligibilityStreetName', '#ui-id-2', 'Street');
        } else {
            $this->autocompleteForm($table, 'eligibilityPostCode', '#ui-id-5', 'NPA');
            $this->autocompleteForm($table, 'eligibilityStreetName', '#ui-id-6', 'Street');
        }
        $this->autocompleteForm($table, 'eligibilityStreetNumber', '.ui-menu-item-wrapper', 'Number');
    }

    /**
     * @Then I click the verification button
     */
    public function iClickTheVerificationButton()
    {
        $this->getSession()->getPage()->find('css', '#eligibilitySubmit')->click();
    }

    /**
     * @Given /^I should see the warning of an invalid address$/
     */
    public function iShouldSeeTheWarningOfAnInvalidAddress($text)
    {
        $this->getSession()->getPage()->find('css', '#error_invalid_address')->isVisible();
        $this->iWaitForTextToAppear($text);
    }

    /**
     * @When I wait for :text to appear
     * @Then I should see :text appear
     * @param $text
     * @throws \Exception
     */
    public function iWaitForTextToAppear($text)
    {
        $this->spin(function (FeatureContext $context) use ($text) {
            try {
                $context->assertPageContainsText($text);
                return true;
            } catch (ResponseTextException $e) {
                // NOOP
            }
            return false;
        });
    }


    /**
     * @When I wait for :text to disappear
     * @Then I should see :text disappear
     * @param $text
     * @throws \Exception
     */
    public function iWaitForTextToDisappear($text)
    {
        $this->spin(function (FeatureContext $context) use ($text) {
            try {
                $context->assertPageContainsText($text);
            } catch (ResponseTextException $e) {
                return true;
            }
            return false;
        });
    }

    /**
     * @Given /^I should see the message of the product availability "([^"]*)"$/
     */
    public function iShouldSeeTheMessageOfTheProductAvailability($text)
    {
        $this->iWaitForTextToAppear($text);
    }

    /**
     * @Then /^I should see a spinner running$/
     */
    public function iShouldSeeASpinnerRunning()
    {
        $this->getSession()->getPage()->find('css', '#spinnerElig')->isVisible();
    }

    /**
     * @Then /^I click checkout button$/
     */
    public function iClickCheckoutButton()
    {
        $this->getSession()->getPage()->find('css', "#fiberEligOk > a")->click();
    }

    /**
     * @When /^I check the "([^"]*)" checkbox of the "([^"]*)"$/
     * @Then /^I check "([^"]*)" checkbox$/
     */
    public function iTickTheCheckbox($labelText, $productCode = null)
    {
        $page = $this->getSession()->getPage();
        $checkboxes = $page->findAll('css', 'label.label_checkbox');

        foreach ($checkboxes as $checkbox) {
            if ($checkbox->getText() == $labelText) {
                $attribute = $checkbox->getAttribute('product_code');
                if ($attribute == $productCode) {
                    $checkbox->find('css', '.checkbox')->click();
                }
            }
        }
        $this->getSession()->wait(10000, '(0 === jQuery.active)');
    }

    public function getProductId($productCode)
    {
        $page = $this->getSession()->getPage();
        $inputIds = $page->findAll('css', '.dsl_provider.required');

        foreach ($inputIds as $inputId) {
            if ($inputId->getAttribute('product_code') == $productCode) {
                $inputId->getAttribute('id');
            }
        }
    }

    public function checkIfCheckboxIsChecked($productCode)
    {
        $productId = $this->getProductId($productCode);
        $checkedElement = $productId->find('css', 'input[type="checkbox"]:checked#' . $productId);
        print $checkedElement . ' ---- checked element';
        if (!$checkedElement) {
            throw new \Exception('Checkbox with id ' . $productId . ' is not checked');
        }
    }

    public function acceptAlert()
    {
        /** @var \Behat\Mink\Driver\Selenium2Driver $driver Needed because no cross-driver way yet */
        $driver = $this->getSession()->getDriver();

        /** Accept the alert so you don't get a runtime exception when the steps try to continue **/
        $driver->getWebDriverSession()->accept_alert();
    }

    /**
     * @Then /^I should be switched to the next step "([^"]*)"$/
     */
    public function iShouldBeSwitchedToTheNextStep($stepName)
    {
        $this->assertElementContains('.maintabNav > li.active > a > span', $stepName);
    }

    /**
     * @Then /^I should see "([^"]*)" of "([^"]*)" in the field$/
     * @Then /^I should see (.*) of "([^"]*)" in the field$/
     * @Then /^I should see (.*) of errors on each field$/
     */
    public function iShouldSeeOfInTheField($num)
    {
        $this->assertSession()->elementsCount('css', '.required.error', intval($num));
    }

    /**
     * @Given /^I fill in the content form with random credentials$/
     */
    public function iFillInTheContentFormWith(TableNode $table)
    {
        $page = $this->getSession()->getPage();
        $hash = $table->getHash();

        foreach ($hash as $row) {
            $field = $page->findField($row['Field']);

            $id = $field->getAttribute('id');
            $options = $field->findAll('named', array('option', $row['Title']));
            foreach ($options as $option) {
                $value = $option->getValue();
            }

            $js = "jQuery('#$id').val('$value');
                           jQuery('#$id').trigger('chosen:updated');
                           jQuery('#$id').trigger('change');";

            $this->getSession()->executeScript($js);
        }

        $this->fillFieldWithRandomName('checkout_service_address_firstname');
        $this->fillFieldWithRandomSurname('checkout_service_address_lastname');
        $this->fillFieldWithRandomMail('email');
        $this->fillFieldWithExistentMail('email_conf');
        $this->fillFieldWithRandomPhone('lead_mobile_phone');
    }

    /**
     * @Given /^I check radio button with "([^"]*)"$/
     */
    public function iCheckRadioButtonWith($text)
    {
        $page = $this->getSession()->getPage();
        $radioButtons = $page->findAll('css', '.activation-option-title');

        foreach ($radioButtons as $radioButton) {
            if ($radioButton->getText() == $text) {
                $radioButton->click();
            }
        }
    }

    /**
     * Click some text
     *
     * @When /^I click on the text "([^"]*)"$/
     */
    public function iClickOnTheText($text)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', '*//*[text()="'. $text .'"]')
        );
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }

        $element->click();

    }

    /**
     * @Then /^I fill in random contact details$/
     */
    public function iFillInRandomContactDetails()
    {
        $this->fillFieldWithRandomLoremIpsum('comment');
        $this->fillFieldWithRandomName('firstname');
        $this->fillFieldWithRandomSurname('name');
        $this->fillFieldWithRandomPhone('telephone');
        $this->fillFieldWithRandomMail('email');
        $this->fillFieldWithRandomNumber('customernumber');
    }
}
