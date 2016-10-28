<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
require_once __DIR__.'/Model/User.php';

class AccountPageContext extends PageObjectContext
{
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
        $this->getPage('AccountPage')->enterUserDetailsOnAccountForm($this->user);
    }
}