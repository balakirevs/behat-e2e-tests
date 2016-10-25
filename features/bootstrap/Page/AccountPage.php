<?php

namespace Page;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class AccountPage extends Page
{

    /**
     * @var string $user
     */
    public function enterUserDetailsOnAccountForm($user)
    {
        return $this->getElement('AccountForm')->enterUserDetails($user);
    }

    /**
     * @var $text
     */
    public function submitAccountForm($text)
    {
        return $this->getElement('AccountForm')->submitForm($text);
    }
}