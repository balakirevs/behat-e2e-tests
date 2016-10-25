<?php

namespace Page\Element;
require_once __DIR__.'/../../Model/User.php';
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
/**
 * Features context.
 */
class AccountForm extends Element
{
    /**
     * @var array $selector
     */
    protected $selector = array('css' => '#crm');

    /**
     * enterUserDetails
     *
     * @param mixed $user
     * @access public
     * @return void
     */
    public function enterUserDetails($user) {
        $this->fillField("id", $user->id);
        $this->fillField("username", $user->username);
        $this->fillField("password", $user->password);
    }

    /**
     * submitForm
     *
     * @access public
     * @return void
     */
    public function submitForm($text) {
        $this->pressButton($text);
    }
}