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
            $this->getSession()->wait(10000, '(0 === jQuery.active)');
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
}