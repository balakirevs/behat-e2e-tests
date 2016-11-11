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
     *  Fill in checkout mobile number
     */
    public function fillInCheckoutPhoneNumber()
    {
        $this->fillFieldWithRandomPhone('lead_mobile_phone');
    }

    /**
     * Fill in first enabled element day on the DatePicker
     */
    public function fillInDateInTheCalendar()
    {
        $this->find('css', '#calendarWrapper')->click();
        $values =  $this->findAll('css', 'a.ui-state-default');
        $values[0]->click();
    }
}