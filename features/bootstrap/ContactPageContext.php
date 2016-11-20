<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class ContactPageContext extends PageObjectContext
{

    public function __construct(){}

    public function __call($method, $parameters)
    {
        $page = $this->getPage('ContactPage');
        if (method_exists($page, $method)) {
            return call_user_func_array(array($page, $method), $parameters);
        }
    }

    private $featureContext;

    /**
     * @param BeforeScenarioScope $scope
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->featureContext = $environment->getContext('FeatureContext');
    }

    /**
     * @Then /^I fill in random contact details$/
     */
    public function iFillInRandomContactDetails()
    {
        $this->fillInContactForm();
    }

    /**
     * Attaches file to field with specified id|name|label|value
     * Example: When I attached "bwayne_profile.png" to "profileImageUpload"
     * Example: And I attached "bwayne_profile.png" to "profileImageUpload"
     *
     * @When /^(?:|I )attached the file "(?P<path>[^"]*)" to "(?P<field>(?:[^"]|\\")*)"$/
     */
    public function attachFileToTheField($field, $path)
    {
        if ($this->featureContext->getMinkParameter('files_path')) {
            $fullPath = rtrim(realpath($this->featureContext->getMinkParameter('files_path')), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$path;
            if (is_file($fullPath)) {
                $path = $fullPath;
            }
        }
        $js = "$('#$field').css('display', 'block')";
        $this->featureContext->executeScript($js);

        $this->featureContext->attachFileToField($field, $path);
    }
}