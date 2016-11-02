<?php

namespace Page;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use Behat\Gherkin\Node\TableNode;
use Assert\Assertion;

class HomePage extends Page
{
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
            $this->jqueryWait(20000);
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

    private function jqueryWait($duration = 1000)
    {
        $this->getSession()->wait($duration, '(0 === jQuery.active && 0 === jQuery(\':animated\').length)');
    }

    private function jsWait($duration = 1000)
    {
        $this->getSession()->wait($duration, '(0 === jQuery.active)');
    }
}