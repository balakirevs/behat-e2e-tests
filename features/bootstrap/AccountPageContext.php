<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
require_once __DIR__.'/Model/User.php';

class AccountPageContext extends PageObjectContext
{
    public function __construct(){}

    public function __call($method, $parameters)
    {
        $page = $this->getPage('AccountPage');
        if (method_exists($page, $method)) {
            return call_user_func_array(array($page, $method), $parameters);
        }
    }
    /**
     *
     */
    var $user = NULL;

    /**
     * @When /^"([^"]*)" exists in a system$/
     */
    public function userExistsInTheSystem($name)
    {
        if (!$this->user = User::load($name)) {
            throw new Exception("Failed to load user for {$name}");
        }
    }

    /**
     * @Then /^user fills in the account form$/
     * @Then /^I fill in the account form$/
     */
    public function userFillsInTheAccountForm()
    {
        if (!$this->user) {
            throw new Exception("User not loaded");
        }
        $this->enterUserDetailsOnAccountForm($this->user);
    }
}