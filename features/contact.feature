Feature: Send Contact Details

  Background:
    Given the browser is set to size

  @javascript @contact
  Scenario: Fill in contact form
    When I am on "/contact"
    Then I fill in random contact details
    When I attach the file "test_file.pdf" to "attachment"
    And I press "Soumettre"
    Then I should be redirected to "/contact/success"