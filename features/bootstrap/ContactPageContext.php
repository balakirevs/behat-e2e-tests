<?php

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;

class ContactPageContext extends PageObjectContext
{

    public function __construct()
    {
    }

    /**
     * @Then /^I fill in random contact details$/
     */
    public function iFillInRandomContactDetails()
    {
        $this->getPage('ContactPage')->fillInContactForm();
    }
}