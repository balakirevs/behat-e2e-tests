<?php

namespace MinkFieldRandomizer\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ElementNotFoundException;
use MinkFieldRandomizer\Filter\FilterEngine;

trait FilterContext
{

    private $loremipsum;

    private $email;

    private $phone;

    private $name;

    private $surname;

    private $number;

    private $text;

    public function setLoremIpsum($loremipsum)
    {
        $this->loremipsum = $loremipsum;
        return $this;
    }

    public function getLoremIpsum()
    {
        return $this->loremipsum;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function filterValue($value)
    {
        return (new FilterEngine())->filter($value);
    }

    /**
     * @Then Fill :field with a random loremipsum
     * @param $field
     */
    public function fillFieldWithRandomLoremIpsum($field)
    {
        $value = $this->filterValue('{RandomLoremIpsum}');
        $this->setLoremIpsum($value);
        $this->fillRandomField($field, $value);
    }

    /**
     * @Then Fill :field with an existent loremipsum
     * @param $field
     */
    public function fillFieldWithExistentLoremIpsum($field)
    {
        $this->fillRandomField($field, $this->getLoremIpsum());
    }

    /**
     * @Then Fill :field with a random email
     * @param $field
     */
    public function fillFieldWithRandomEmail($field)
    {
        $value = $this->filterValue('{RandomEmail}');
        $this->setEmail($value);
        $this->fillRandomField($field, $value);
    }

    /**
     * @Then Fill :field with an existent email
     * @param $field
     */
    public function fillFieldWithExistentEmail($field)
    {
        $this->fillRandomField($field, $this->getEmail());
    }

    /**
     * @Then Fill :field with a random phone
     * @param $field
     */
    public function fillCheckoutFieldWithRandomPhone($field)
    {
        $value = $this->filterValue('079{RandomPhone(7)}');
        $this->setPhone($value);
        $this->fillRandomField($field, $value);
    }

    /**
     * @Then Fill billing :field with a random phone
     * @param $field
     */
    public function fillBillingFieldWithRandomPhone($field)
    {
        $value = $this->filterValue('79{RandomPhone(7)}');
        $this->setPhone($value);
        $this->fillRandomField($field, $value);
    }

    /**
     * @Then Fill :field with an existent phone
     * @param $field
     */
    public function fillFieldWithExistentPhone($field)
    {
        $this->fillRandomField($field, $this->getPhone());
    }

    /**
     * @Then Fill :field with a random name
     * @param $field
     */
    public function fillFieldWithRandomName($field)
    {
        $value = $this->filterValue('{RandomName}');
        $this->setName($value);
        $this->fillRandomField($field, $value);
    }

    /**
     * @Then Fill :field with an existent name
     * @param $field
     */
    public function fillFieldWithExistentName($field)
    {
        $this->fillRandomField($field, $this->getName());
    }

    /**
     * @Then Fill :field with a random surname
     * @param $field
     */
    public function fillFieldWithRandomSurname($field)
    {
        $value = $this->filterValue('{RandomSurname}');
        $this->setSurname($value);
        $this->fillRandomField($field, $value);
    }

    /**
     * @Then Fill :field with an existent surname
     * @param $field
     */
    public function fillFieldWithExistentSurname($field)
    {
        $this->fillRandomField($field, $this->getSurname());
    }

    /**
     * @Then Fill :field with a random number
     * @param $field
     */
    public function fillFieldWithRandomNumber($field)
    {
        $value = $this->filterValue('{RandomNumber}');
        $this->setNumber($value);
        $this->fillRandomField($field, $value);
    }

    /**
     * @Then Fill :field with an existent number
     * @param $field
     */
    public function fillFieldWithExistentNumber($field)
    {
        $this->fillRandomField($field, $this->getNumber());
    }

    /**
     * @Then Fill :field with a random text
     * @param $field
     */
    public function fillFieldWithRandomText($field)
    {
        $value = $this->filterValue('{RandomText}');
        $this->setText($value);
        $this->fillRandomField($field, $value);
    }

    /**
     * @Then Fill :field with an existent text
     * @param $field
     */
    public function fillFieldWithExistentText($field)
    {
        $this->fillRandomField($field, $this->getText());
    }

    /**
     * Checks, that form field with specified id|name|label|value has registered value.
     *
     * @Then /^the "(?P<field>(?:[^"]|\\")*)" field should contains "(?P<value>(?:[^"]|\\")*)" value$/
     * @param $field
     * @param $value
     */
    public function assertFieldContainsValue($field, $value)
    {
        $value = $this->{$value};
        return $this->assertSession()->fieldValueEquals($field, $value);
    }

    public function assertFieldValue($field, $value)
    {
        return $this->fieldValueEquals($field, $value);
    }

    public function fieldValueEquals($field, $value)
    {
        $container = $this->getSession()->getPage()->findField($field);
        $actual = $container->getValue();
        $regex = '/^'.preg_quote($value, '/').'$/ui';

        return preg_match($regex, $actual);
    }

    /**
     * @Then Fill :field with :value
     * @param $field
     * @param $value
     */
    public function fillRandomField($field, $value)
    {
        $this->fillField($field, $this->filterValue($value));
    }

    /**
     * Fills in form fields with provided table.
     */
    public function fillFilteredFields(TableNode $fields)
    {
        foreach ($fields->getRowsHash() as $field => $value) {
            $this->fillRandomField($field, $value);
        }
    }

    /**
     * Selects option in select field with specified id|name|label|value.
     *
     * @Then I select :arg1 option from :arg2
     *
     * @param $select
     * @param $option
     * @throws ElementNotFoundException
     */
    public function iSelectOptionFrom($option, $select)
    {
        $this->getSession()->getPage()->selectFieldOption($select, $this->filterValue($option));
    }
}