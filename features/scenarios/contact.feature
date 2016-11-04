Feature: Send Contact Details

  Background:
    Given the browser is set to size

  @javascript @contact
  Scenario Outline: Fill in contact form
    Given I am on "/<locale>/contact"
    Then I fill in random contact details
    When I attached the file "test_file.pdf" to "attachment"
    And I press "<Button>"
    Then I should be redirected to "/<locale>/contact/success"
  Examples:
    | locale | Button    |
    | fr     | Soumettre |