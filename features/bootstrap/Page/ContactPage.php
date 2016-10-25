<?php

namespace Page;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use MinkFieldRandomizer\Context\FilterContext;

class ContactPage extends Page
{
    use FilterContext;

    /**
     * Fill in contact form with random data
     */
    public function fillInContactForm()
    {
        $this->fillFieldWithRandomLoremIpsum('comment');
        $this->fillFieldWithRandomName('firstname');
        $this->fillFieldWithRandomSurname('name');
        $this->fillFieldWithRandomPhone('telephone');
        $this->fillFieldWithRandomEmail('email');
        $this->fillFieldWithRandomNumber('customernumber');
    }
}