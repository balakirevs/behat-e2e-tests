<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use MinkFieldRandomizer\Context\FilterContext;
require_once __DIR__.'/Model/User.php';

class AccountPageContext extends PageObjectContext
{
    /**
     *
     */
    var $user = NULL;

    /**
     * @When /^"([^"]*)" is on the account page$/
     */
    public function userIsOnTheAccountPage($name)
    {
        if (!$this->user = User::load($name)) {
            throw new Exception("Failed to load user for {$name}");
        }
    }

    /**
     * @Then /^user fills in the account form$/
     */
    public function userFillsInTheAccountForm()
    {
        if (!$this->user) {
            throw new Exception("User not loaded");
        }
        $this->getPage('AccountPage')->enterUserDetailsOnAccountForm($this->user);
    }
}