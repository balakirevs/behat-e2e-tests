<?php

namespace Page;
use Behat\Gherkin\Node\TableNode;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use MinkFieldRandomizer\Context\FilterContext;
use Assert\Assertion;

class CheckoutPage extends Page
{
    use FilterContext;

    protected $elements = array(
        'Accept conditions' => '#info_term',
        'Datepicker' => '#calendarWrapper',
        'Continue Button' => array('css' => '#co-billing-form > div.button_bar_checkout > button.button.next'),
        'Confirmation email' => array('css' => '#my_success > div.content.page-too-small > div > p:nth-child(5)'),
        'Operator name' => array('css' => '#portability_detail_selection_review > div > ul > li:nth-child(1) > span'),
        'Contract type' => array('css' => '#portability_detail_selection_review > div > ul > li:nth-child(2) > span'),
        'Term info' => array('css' => '#portability_detail_selection_review > div > ul > li:nth-child(4) > span'),
        'Title name' => array('css' => '#checkout-end-billing-name-surname > div > ul > li:nth-child(1) > span'),
        'Checkout email' => array('css' => '#checkout-end-billing-address > div > ul > li:nth-child(1) > span'),
        'Birth day' => array('css' => '#checkout-end-billing-nationailty > div > ul > li:nth-child(1) > span'),
        'Origin' => array('css' => '#checkout-end-billing-nationailty > div > ul > li:nth-child(2) > span'),
        'Passport' => array('css' => '#checkout-end-billing-nationailty > div > ul > li:nth-child(3) > span')
    );

    /**
     * @param TableNode $table
     * Fill in checkout title details
     */
    public function fillInCheckoutTitleDetails(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $this->selectFieldOption($row['Field'], $row['Title']);
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
     *  Fill in checkout mobile personal details: firstName, lastName
     */
    public function fillInMinorUserDetails()
    {
        $this->fillFieldWithRandomName('custom_step2[firstname_underage]');
        $this->fillFieldWithRandomSurname('custom_step2[lastname_underage]');
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
        $this->fillCheckoutFieldWithRandomPhone('lead_mobile_phone');
    }

    /**
     *  Fill in billing mobile number
     */
    public function fillInBillingPhoneNumber()
    {
        $this->fillBillingFieldWithRandomPhone('contact_phone_number');
    }

    /**
     * Fill in first enabled element day on the DatePicker
     */
    public function fillInDateInTheCalendar()
    {
        $this->getElement('Datepicker')->click();
        $values = $this->findAll('css', 'a.ui-state-default');
        $values[0]->click();
    }

    /*
     * Fill in form field
     */
    public function fillInFormField($selector, $name)
    {
        $this->selectFieldOption($selector, $name);
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
    public function fillInPassportNumber()
    {
        $this->fillFieldWithRandomNumber('custom_step2_document_type_passport_value');
    }

    /*
     * Fill in Suisse id card number
     */
    public function fillInIdCardNumber()
    {
        $this->fillFieldWithRandomNumber('custom_step2_document_type_IdCard_value1');
    }

    /*
     * Fill in Foreigner's piece of legitimation
     */
    public function fillInPieceLegitimation()
    {
        $this->fillFieldWithRandomNumber('custom_step2_document_type_permit_value1');
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
        $this->getElement('Accept conditions')->click();
    }

    /*
     * Press button Continue
     */
    public function clickButtonContinue()
    {
        $this->getElement('Continue Button')->click();
    }

    /*
     * Check confirmation email on the Acknowledgment Page
     */
    public function checkConfirmationEmail($email)
    {
        $text = $this->getElement('Confirmation email')->getText();
        Assertion::true(strpos($text, $email) !== false);
    }

    /*
     * Verify previously filled in operator name
     */
    public function checkCheckoutOperatorName(TableNode $table)
    {
        $this->getSession()->wait(3000);
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $operator = $this->getElement('Operator name')->getText();
            Assertion::eq($operator, $row['Operator']);
        }
    }

    /*
     * Verify previously filled in contract type
     */
    public function checkCheckoutContractType(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $contractType = $this->getElement('Contract type')->getText();
            Assertion::eq($contractType, $row['ContractType']);
        }
    }

    /*
     * Verify previously filled in term
     */
    public function checkCheckoutTerm(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $term = $this->getElement('Term info')->getText();
            Assertion::eq($term, $row['Term']);
        }
    }

    /*
     * Verify previously filled in title
     */
    public function checkCheckoutTitle(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $title = $this->getElement('Title name')->getText();
            Assertion::eq($title, $row['Title']);
        }
    }

    /*
     * Verify previously filled in email
     */
    public function checkCheckoutEmail(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $email = $this->getElement('Checkout email')->getText();
            Assertion::eq($email, $row['Email']);
        }
    }

    /*
     * Verify previously filled in birthday
     */
    public function checkCheckoutBirthDay(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $birthday = $this->getElement('Birth day')->getText();
            Assertion::eq($birthday, $row['BirthDay']);
        }
    }

    /*
     * Verify previously filled in nationality
     */
    public function checkCheckoutNationality(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $nationality = $this->getElement('Origin')->getText();
            Assertion::eq($nationality, $row['Nationality']);
        }
    }

    /*
     * Verify previously filled in IdCard
     */
    public function checkCheckoutIdCard(TableNode $table)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $idCard = $this->getElement('Passport')->getText();
            Assertion::eq($idCard, $row['IdCard']);
        }
    }
}