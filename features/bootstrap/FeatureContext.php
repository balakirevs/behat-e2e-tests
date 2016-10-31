<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Exception\ResponseTextException;
use Behat\MinkExtension\Context\MinkContext;
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

        throw new \RuntimeException(sprintf(
            'The "%s()" method does not exist.', $method
        ));
    }

    /**
     * Example: A Domain is set to domain name "wingo" and domain "ch"
     *
     * @Given A Domain is set to a subDomain :subDomain and domain name :domainName and domain type :domainType
     * @When A Domain is set to domain name :domainName and domain type :domainType
     */
    public function navigateToUrl($subDomain = null, $domainName, $domainType)
    {
        $base_url = $this->setUpUrl($subDomain, $domainName, $domainType);
        $this->setMinkParameter('base_url', $base_url);
    }

    /**
     * @When I click :arg1 link
     */
    public function iClickLink($link)
    {
        $this->clickLink($link);
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
     * @Given /^I check radio button with "([^"]*)"$/
     */
    public function iCheckRadioButtonWith($text)
    {
        $radioButtons = $this->findAll('css', '.activation-option-title');

        foreach ($radioButtons as $radioButton) {
            if ($radioButton->getText() == $text) {
                $radioButton->click();
            }
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

    public function getProtocol()
    {
        $protocol = ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? "https://" : "http://");
        return $protocol;
    }

    public function setSubDomain($subDomain)
    {
        $this->subDomain = $subDomain;
        return $this;
    }

    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;
        return $domainName;
    }

    public function setDomainType($domainType)
    {
        $this->domainType = $domainType;
        return $this;
    }

    public function setUpUrl($subDomain = null, $domainName, $domainType)
    {
        $protocol = $this->getProtocol();
        $this->setDomainName($domainName);
        $this->setDomainType($domainType);
        $base_url = $protocol . $subDomain . '.' . $domainName . '.' . $domainType;
        if (!$subDomain || $subDomain == null) {
            $pos = strpos($base_url, '.');
            if ($pos !== false) {
                $url = substr_replace($base_url, '', $pos, strlen('.'));
                return $url;
            }
        }
        return $base_url;
    }

    /**
     * Attaches file to field with specified id|name|label|value
     * Example: When I attach "bwayne_profile.png" to "profileImageUpload"
     * Example: And I attach "bwayne_profile.png" to "profileImageUpload"
     *
     * @When /^(?:|I )attach the file "(?P<path>[^"]*)" to "(?P<field>(?:[^"]|\\")*)"$/
     */
    public function attachFileToTheField($field, $path)
    {
        $field = $this->fixStepArgument($field);

        if ($this->getMinkParameter('files_path')) {
            $fullPath = rtrim(realpath($this->getMinkParameter('files_path')), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$path;
            if (is_file($fullPath)) {
                $path = $fullPath;
            }
        }
        $js = "$('#$field').css('display', 'block')";
        $this->getSession()->executeScript($js);

        $this->getSession()->getPage()->attachFileToField($field, $path);
    }
}