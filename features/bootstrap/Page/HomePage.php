<?php

namespace Page;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use Behat\Gherkin\Node\TableNode;
use Assert\Assertion;

class HomePage extends Page
{
    protected $elements = array(
        'Menu' => '.burger-icon',
        'Reset' => array('css' => '#fiberEligOk'),
        'Eligibility form' => '#eligibilityFormContainer',
        'Verification Button' => '#eligibilitySubmit',
        'Spinner' => '#spinnerElig'
    );

    /**
     * @param TableNode $table
     * @param string $element
     * @params string $autocompleteElement
     * @params string $formInput
     */
    public function fillInAutocompleteForm(TableNode $table, $element, $autocompleteElement, $formInput)
    {
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $this->fillField($element, $row[$formInput]);
            $this->findById($element)->click();
            $results = $this->findAll('css', $autocompleteElement);
            Assertion::notNull($results);
            foreach ($results as $result) {
                if ($result->getText() == $row[$formInput]) {
                    $result->click();
                    return;
                }
            }
        }
    }

    /*
     * Wait for JS element
     */
    private function jqueryWait($duration = 1000)
    {
        $this->getSession()->wait($duration, '(0 === jQuery.active && 0 === jQuery(\':animated\').length)');
    }

    /*
     * Enable element id
     */
    public function enableElementId($element)
    {
        $js = "jQuery('#$element').prop('disabled', false);";
        $this->getSession()->executeScript($js);
        $this->jqueryWait(10000);
    }

    /*
     * Reset fiber eligibility
     */
    public function resetFiberEligibility()
    {
        $form = $this->getElement('Reset')->isVisible();
        $js = "clearAndShowForm();";
        if ($form) {
            $this->getSession()->executeScript($js);
        }
    }

    /*
     * Click menu icon
     */
    public function clickMenu(){
        $this->getElement('Menu')->click();
    }

    /*
     * See the presence of eligibility element
     */
    public function checkIfEligibilityElementExists()
    {
        $this->getElement('Eligibility form')->isVisible();
    }

    /*
     * Press verification button
     */
    public function clickVerificationButton()
    {
        $this->getElement('Verification Button')->click();
    }

    /*
     * See if the spinner is running
     */
    public function runningSpinner()
    {
        $this->getElement('Spinner')->isVisible();
    }
}