<?php

namespace Page;
use Behat\Gherkin\Node\TableNode;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use MinkFieldRandomizer\Context\FilterContext;

class CheckoutPage extends Page
{
    use FilterContext;

    /**
     * @param TableNode $table
     * Fill in checkout title details
     */
    public function fillInCheckoutTitleDetails(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $field = $this->findField($row['Field']);
            $id = $field->getAttribute('id');
            $options = $field->findAll('named', array('option', $row['Title']));
            foreach ($options as $option) {
                $value = $option->getValue();
            }
            $js = "jQuery('#$id').val('$value');
                   jQuery('#$id').trigger('chosen:updated');
                   jQuery('#$id').trigger('change');";
            $this->getSession()->executeScript($js);
        }
    }

    /**
     *  Fill in checkout personal details: firstName, lastName
     */
    public function fillInCheckoutPersonalDetails()
    {
        $this->fillFieldWithRandomName('checkout_service_address_firstname');
        $this->fillFieldWithRandomSurname('checkout_service_address_lastname');
    }

    /**
     *  Fill in checkout mobile personal details: firstName, lastName
     */
    public function fillInCheckoutMobilePersonalDetails()
    {
        $this->fillFieldWithRandomName('billing[firstname]');
        $this->fillFieldWithRandomSurname('billing[lastname]');
    }

    /**
     *  Fill in checkout email: email, confirmation email
     */
    public function fillInCheckoutEmailAddress()
    {
        $this->fillFieldWithRandomEmail('email');
        $this->fillFieldWithExistentEmail('email_conf');
    }

    /**
     *  Fill in billing email: email, confirmation email
     */
    public function fillInBillingEmailAddress()
    {
        $this->fillFieldWithRandomEmail('billing_email');
        $this->fillFieldWithExistentEmail('billing_email_confirm');
    }

    /**
     *  Fill in checkout mobile number
     */
    public function fillInCheckoutPhoneNumber()
    {
        $this->fillFieldWithRandomPhone('lead_mobile_phone');
    }

    /**
     *  Fill in billing mobile number
     */
    public function fillInBillingPhoneNumber()
    {
        $this->fillFieldWithRandomPhone('contact_phone_number');
    }

    /**
     * Fill in first enabled element day on the DatePicker
     */
    public function fillInDateInTheCalendar()
    {
        $this->find('css', '#calendarWrapper')->click();
        $values = $this->findAll('css', 'a.ui-state-default');
        $values[0]->click();
    }

    /*
     * Fill in form field
     */
    public function fillInFormField($selector, $name)
    {
        $field = $this->findField($selector);
        $id = $field->getAttribute('id');
        $options = $field->findAll('named', array('option', $name));
        foreach ($options as $option) {
            $value = $option->getValue();
        }
        $js = "jQuery('#$id').val('$value');
               jQuery('#$id').trigger('chosen:updated');
               jQuery('#$id').trigger('change');";
        $this->getSession()->executeScript($js);
    }

    /*
     * Select nationality
     */
    public function selectNationality($nationality)
    {
        $this->selectFieldOption('custom_step2_country_id', $nationality);
    }

    /*
     * Select Identity Card
     */
    public function selectIdentityCard($card)
    {
        $this->selectFieldOption('custom_step2_type_of_swiss_id', $card);
    }

    /*
     * Fill in Suisse passport number
     */
    public function fillInPassportNumber($number)
    {
        $this->fillField('custom_step2_document_type_passport_value', $number);
    }

    /*
     * Fill in Suisse id card number
     */
    public function fillInIdCardNumber($number)
    {
        $this->fillField('custom_step2_document_type_IdCard_value1', $number);
    }

    /*
     * Fill in Foreigner's piece of legitimation
     */
    public function fillInPieceLegitimation($number)
    {
        $this->fillField('custom_step2_document_type_permit_value1', $number);
    }

    /*
     * Fill in password
     */
    public function fillInPassword($password)
    {
        $this->fillField('custom_step2_password', $password);
    }

    /*
     * Fill in password confirmation
     */
    public function fillInPasswordConfirmation($password)
    {
        $this->fillField('custom_step2_password_confirm', $password);
    }

    public function acceptWingoCheckoutConditions()
    {
        $this->find('css', "#info_term")->click();
    }

    /*
     * Press button Continue
     */
    public function clickButtonContinue()
    {
        $this->find('css', '#co-billing-form > div.button_bar_checkout > button.button.next')->click();
    }
}