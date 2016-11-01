<?php

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;

class NavigationPageContext extends PageObjectContext
{
    public function __construct()
    {
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
     * Example: A Domain is set to domain name "wingo" and domain "ch"
     *
     * @Given A Domain is set to a subDomain :subDomain and domain name :domainName and domain type :domainType
     * @When A Domain is set to domain name :domainName and domain type :domainType
     */
    public function navigateToUrl($subDomain = null, $domainName, $domainType)
    {
        $base_url = $this->getPage('NavigationPage')->setUpUrl($subDomain, $domainName, $domainType);
        $this->featureContext->setMinkParameter('base_url', $base_url);
    }
}