<?php

namespace Page;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class AccountPage extends Page
{
    public function __call($method, $parameters)
    {
        $page = $this->getElement('AccountForm');
        if (method_exists($page, $method)) {
            return call_user_func_array(array($page, $method), $parameters);
        }
    }

    /**
     * @var string $user
     */
    public function enterUserDetailsOnAccountForm($user)
    {
        return $this->enterUserDetails($user);
    }

    /**
     * @var $text
     */
    public function submitAccountForm($text)
    {
        return $this->submitForm($text);
    }
}