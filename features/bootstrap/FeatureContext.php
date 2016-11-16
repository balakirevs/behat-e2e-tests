<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Exception\ResponseTextException;
use Behat\MinkExtension\Context\MinkContext;
use MinkFieldRandomizer\Context\FilterContext;
use Behat\Gherkin\Node\TableNode;
use Assert\Assertion;

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
    private $params = array();

    public function __construct($parameters)
    {
        $this->params = $parameters;
    }

    public function getParameter($name)
    {
        if (count($this->params) === 0) {
            throw new \Exception('Parameters not loaded!');
        } else {
            $parameters = $this->params;
            return (isset($parameters[$name])) ? $parameters[$name] : null;
        }
    }

    public function __call($method, $parameters)
    {
        $page = $this->getSession()->getPage();
        if (method_exists($page, $method)) {
            return call_user_func_array(array($page, $method), $parameters);
        }

        $session = $this->getSession();
        if (method_exists($session, $method)) {
            return call_user_func_array(array($session, $method), $parameters);
        }

        $driver = $this->getSession()->getDriver();
        if (method_exists($driver, $method)) {
            return call_user_func_array(array($driver, $method), $parameters);
        }

        $assert = $this->assertSession();
        if (method_exists($assert, $method)) {
            return call_user_func_array(array($assert, $method), $parameters);
        }

        $selectorsHandler = $session->getSelectorsHandler();
        if (method_exists($selectorsHandler, $method)) {
            return call_user_func_array(array($selectorsHandler, $method), $parameters);
        }

        throw new \RuntimeException(sprintf(
            'The "%s()" method does not exist.', $method
        ));
    }

    /**
     * @When /^I click "([^"]*)" link "([^"]*)"$/
     * @When I click :arg1 link
     */
    public function iClickLink1($text, $locale = null)
    {
        $prod_link = $this->params['href_prod'] . $locale . $this->params['product_url'];
        $link = $this->getLinkByTextValue($text);
        $href = $link->getAttribute('href');
        if ($href == $prod_link){
            $this->replacePartOfHrefAttribute('shop', 'bbg-preprod');
            $link->click();
        }else {
            $this->clickLink($text);
        }
    }

    public function getLinkByTextValue($text)
    {
        return $this->find('named', array('link', $this->xpathLiteral($text)));
    }

    public function getLinkAttributeByTextValue($text)
    {
        $link = $this->find('named', array('link', $this->xpathLiteral($text)));
        return $link->getAttribute('href');
    }

    public function replacePartOfHrefAttribute($href, $newHref)
    {
        $js = "$(document).ready(function(){ $('a').each(function(){ this.href = this.href.replace('$href', '$newHref'); });});";
        $this->executeScript($js);
    }

    /**
     * @Then I should see eligibility form container
     */
    public function iShouldSeeEligibilityFormContainer()
    {
        $this->elementExists('css', '#eligibilityFormContainer');
    }

    /**
     * @When I click button of a :arg1 product
     */
    public function iClickButtonOfAProduct($colour)
    {
        $element = $this->find('css', ".price.$colour-bkg-30 > a");
        $element->click();
    }

    /**
     * @Given I should be redirected to :arg1
     */
    public function iShouldBeRedirectedTo($url)
    {
        $this->waitForUrlRedirect($url);
    }

    /**
     * @When I wait for :url to redirect
     * @Then I should see :url redirect
     * @param $url
     * @throws \Exception
     */
    public function waitForUrlRedirect($url)
    {
        $this->spin(function (FeatureContext $context) use ($url) {
            try {
                $context->assertPageAddress($url);
                return true;
            } catch (ResponseTextException $e) {
            }
            return false;
        });
    }

    /**
     * @Then I click the verification button
     */
    public function iClickTheVerificationButton()
    {
        $this->find('css', '#eligibilitySubmit')->click();
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
                $context->assertPageNotContainsText($text);
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
        $this->find('css', '#spinnerElig')->isVisible();
    }

    /**
     * @Then /^I click checkout button$/
     */
    public function iClickCheckoutButton()
    {
        $this->find('css', "#fiberEligOk > a")->click();
    }

    /**
     * @When /^I check the "([^"]*)" checkbox of the "([^"]*)"$/
     * @Then /^I check "([^"]*)" checkbox$/
     */
    public function iTickTheCheckbox($labelText, $productCode = null)
    {
        $checkboxes = $this->findAll('css', 'label.label_checkbox');

        foreach ($checkboxes as $checkbox) {
            if ($checkbox->getText() == $labelText) {
                $attribute = $checkbox->getAttribute('product_code');
                if ($attribute == $productCode) {
                    $checkbox->find('css', '.checkbox')->click();
                }
            }
        }
        $this->wait(10000, '(0 === jQuery.active)');
    }

    /*
     * @param $productCode
     */
    public function getProductId($productCode)
    {
        $inputIds = $this->findAll('css', '.dsl_provider.required');

        foreach ($inputIds as $inputId) {
            if ($inputId->getAttribute('product_code') == $productCode) {
                $inputId->getAttribute('id');
            }
        }
    }

    /*
     * @param $productId
     * @param $productCode
     * @throws \Exception
     */
    public function checkIfCheckboxIsChecked($productCode)
    {
        $productId = $this->getProductId($productCode);
        $checkedElement = $productId->find('css', 'input[type="checkbox"]:checked#' . $productId);
        if (!$checkedElement) {
            throw new \Exception('Checkbox with id ' . $productId . ' is not checked');
        }
    }

    /**
     * @Then /^I should be switched to the next step "([^"]*)"$/
     */
    public function iShouldBeSwitchedToTheNextStep($stepName)
    {
        $this->assertElementContains('.maintabNav > li.active > a > span', $stepName);
    }

    /**
     * @Then /^I must be switched to the next step "([^"]*)"$/
     */
    public function iAmSwitchedToTheNextStep($stepName)
    {
        $this->wait(5000);
        $this->assertElementContains('.maintabNav > li.active > a > span', $stepName);
    }

    /**
     * @Given /^I check radio button with "([^"]*)"$/
     */
    public function iCheckOTOnumberRadioButtonWith($text)
    {
        $this->checkRadioButtonByCssSelector($text, '.activation-option-title');
    }

    /**
     * @Given /^I check radio button for mobile with "([^"]*)"$/
     */
    public function iCheckMyActualNumberCheckBox($text)
    {
        if (in_array($text, $this->params['actual_number'])) {
            $this->checkRadioButtonByCssSelector($text, '#keep_number');
        } else {
            Assertion::true(in_array($text, $this->params['new_number']));
            $this->checkRadioButtonByCssSelector($text, '#ask_number');
        }
    }

    /**
     * Based on Behat's own example
     * @see http://docs.behat.org/en/v2.5/cookbook/using_spin_functions.html#adding-a-timeout
     * @param $lambda
     * @param int $wait
     * @throws \Exception
     */
    public function spin($lambda, $wait = 60)
    {
        $time = time();
        $stopTime = $time + $wait;
        while (time() < $stopTime)
        {
            try {
                if ($lambda($this)) {
                    return;
                }
            } catch (\Exception $e) {
                // do nothing
            }
            usleep(250000);
        }
        throw new \Exception("Spin function timed out after {$wait} seconds");
    }

    /**
     * @When I wait for :cssSelector
     * @param $cssSelector
     * @throws \Exception
     */
    public function iWaitFor($cssSelector)
    {
        $this->spin(function($context) use ($cssSelector) {
            /** @var $context */
            return !is_null($context->find('css', $cssSelector));
        });
    }

    /*
     * @params $text
     * @params $cssSelector
     */
    public function checkRadioButtonByCssSelector($text, $cssSelector)
    {
        $radioButtons = $this->findAll('css', $cssSelector);

        foreach ($radioButtons as $radioButton) {
            if ($radioButton->getText() == $text) {
                $radioButton->click();
            }
        }
    }

    /**
     * @param TableNode $table
     * @Then /^I select my mobile operator from the list$/
     */
    public function iSelectMyMobileOperatorFromTheList(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $field = $this->findField($row['Field']);
            $id = $field->getAttribute('id');
            $options = $field->findAll('named', array('option', $row['Operator']));
            foreach ($options as $option) {
                $value = $option->getValue();
            }
            $js = "jQuery('#$id').val('$value');
                   jQuery('#$id').trigger('chosen:updated');
                   jQuery('#$id').trigger('change');";
            $this->getSession()->executeScript($js);
        }
    }

    /**
     * @Then /^I fill a random phone number$/
     */
    public function iFillARandomPhoneNumber()
    {
        $this->fillFieldWithRandomPhone('custom_step1_portability_phone_number');
    }

    /**
     * @Given /^I check radio button of portability date end of contract "([^"]*)"$/
     */
    public function iCheckRadioButtonOfPortabilityDateEndDuration($term)
    {
        $this->checkRadioButtonByCssSelector($term, '#portability_date_left');
    }

    /**
     * @Then /^I check radio button of portability date of choice "([^"]*)"$/
     */
    public function iCheckRadioButtonOfPortabilityDateOfChoice($term)
    {
        $this->checkRadioButtonByCssSelector($term, '#portability_date_right');
        $this->wait(10000, '(0 === jQuery.active)');
        $this->find('css', '#close-alert-btn-porta')->click();
    }

    /*
     * Check if element is enabled
     */
    public function isElementEnabled($id, $class) {
        $this->wait(10000, '(0 === jQuery.active)');
        return $this->find('css', $id)->hasClass($class);
    }

    /*
     * Check if portability term field is enabled i.e not greyed
     */
    public function isPortabilityTermFieldDisabled()
    {
        return $this->isElementEnabled('#portability_date_left', 'lightGrey');
    }

    /*
     * Check if contract type field is enabled i.e not greyed
     */
    public function isAbonimentSubscriptionFieldDisabled()
    {
        return $this->isElementEnabled('#portability_current_subscription_left', 'lightGrey');
    }

    /*
     * Check if prepaid subscription is enabled i.e not grayed
     */
    public function isPrepaidSubscriptionDisabled()
    {
        return $this->isElementEnabled('#portability_current_subscription_right', 'lightGrey');
    }

    /*
     * Click aboniment contract subscription
     */
    public function clickAbonimentSubscription($type)
    {
        $this->checkRadioButtonByCssSelector($type, '#portability_current_subscription_left');
    }

    /*
    * Click prepaid contract subscription
    */
    public function clickPrepaidSubscription($type)
    {
        $this->checkRadioButtonByCssSelector($type, '#portability_current_subscription_right');
    }

    public function isOperatorExists($operator)
    {
        return in_array($operator, $this->params['general_operators']) &&
               in_array($operator, $this->params['aboniment_operators']) &&
               in_array($operator, $this->params['prepaid_operators']);
    }

    /**
     * @Given /^I check the contract "([^"]*)" and portability "([^"]*)" according to "([^"]*)"$/
     */
    public function iCheckTheContractTypeAndPortabilityAccordingTo($type, $term, $operator)
    {
        if (in_array($operator, $this->params['general_operators']) && (!in_array($operator, $this->params['aboniment_operators']) && (!in_array($operator, $this->params['prepaid_operators'])))) {
            Assertion::false($this->isAbonimentSubscriptionFieldDisabled());
            Assertion::false($this->isPrepaidSubscriptionDisabled());
            Assertion::false($this->isPortabilityTermFieldDisabled());

            if (in_array($type, $this->params['abonement']) && (!in_array($type, $this->params['prepaid']))){
                $this->clickAbonimentSubscription($type);
                Assertion::false($this->isPrepaidSubscriptionDisabled());
                Assertion::false($this->isPortabilityTermFieldDisabled());

                if (!in_array($term, $this->params['other_term']) && (in_array($term, $this->params['end_term']))) {
                    $this->iCheckRadioButtonOfPortabilityDateEndDuration($term);
                } else {
                    $this->iCheckRadioButtonOfPortabilityDateOfChoice($term);
                }
            } else {
                $this->clickPrepaidSubscription($type);
                Assertion::true($this->isPortabilityTermFieldDisabled());
            }
        } else if (!in_array($operator, $this->params['general_operators']) && (in_array($operator, $this->params['aboniment_operators']) && (!in_array($operator, $this->params['prepaid_operators'])))) {
            Assertion::false($this->isAbonimentSubscriptionFieldDisabled());
            Assertion::true($this->isPrepaidSubscriptionDisabled());
            Assertion::false($this->isPortabilityTermFieldDisabled());

            if (in_array($type, $this->params['abonement']) && (!in_array($type, $this->params['prepaid']))) {
                $this->clickAbonimentSubscription($type);
                Assertion::true($this->isPrepaidSubscriptionDisabled());
                Assertion::false($this->isPortabilityTermFieldDisabled());

                if (!in_array($term, $this->params['other_term']) && (in_array($term, $this->params['end_term']))) {
                    $this->iCheckRadioButtonOfPortabilityDateEndDuration($term);
                } else {
                    $this->iCheckRadioButtonOfPortabilityDateOfChoice($term);
                }
            }
        } else if (!in_array($operator, $this->params['general_operators']) && (!in_array($operator, $this->params['aboniment_operators']) && (in_array($operator, $this->params['prepaid_operators'])))) {
            Assertion::true($this->isAbonimentSubscriptionFieldDisabled());
            Assertion::false($this->isPrepaidSubscriptionDisabled());
            Assertion::true($this->isPortabilityTermFieldDisabled());

            if (!in_array($type, $this->params['abonement']) && (in_array($type, $this->params['prepaid']))) {
                $this->clickPrepaidSubscription($type);
                Assertion::true($this->isPortabilityTermFieldDisabled());

                if (in_array($term, $this->params['other_term']) && (!in_array($term, $this->params['end_term']))) {
                    $this->iCheckRadioButtonOfPortabilityDateOfChoice($term);
                }
            }
        } else {
            Assertion::false($this->isOperatorExists($operator));
            throw new \Exception ($operator . ' is not found');
        }
    }

    /**
     * @When /^I check checkbox of a minor user$/
     */
    public function iCheckCheckboxOfAMinorUser()
    {
        $text = $this->findById('under_age')->getText();
        if (in_array($text, $this->params['minor_user'])) {
            $this->checkRadioButtonByCssSelector($text, '#under_age > label');
        }
    }
}