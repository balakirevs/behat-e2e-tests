@contactForm
Feature: Send Contact Details

  Background:
    Given the browser is set to size

  @javascript @contact
  Scenario Outline: Fill in contact form
    Given I am on "/<locale>/<Url>"
    And I select "Internet" from "requesttype"
    Then I fill in random contact details
    When I attached the file "test_file.pdf" to "attachment"
    And I press "<Button>"
    Then I should be redirected to "/<locale>/<Url>/success"
  Examples:
    | locale | Button    | Url     |
    | fr     | Soumettre | contact |
    | de     | Senden    | kontakt |

  @javascript @contactContent
  Scenario:  Check contact page content
    Given I am on "/fr/contact"
    Then I should see "Kontact"
    When I click "Vers les FAQ" link
    And I should be redirected to "/fr/help"