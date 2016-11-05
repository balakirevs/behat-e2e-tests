Feature: Account

  Background:
   Given the browser is set to size
    And A Domain is set to a subDomain "mywingo" and domain name "wingo" and domain type "ch"

  @javascript @account
  Scenario Outline: Sign in to user account
    And "<User>" exists in a system
    When I am on "<AccountUrl><locale>"
    Then I fill in the account form
    And I press "<Button>"
    Then I should be redirected to "<eCareUrl>"

    Examples:
      | User  | Button             | eCareUrl       | AccountUrl          | locale |
      | gabin | Accéder à my Wingo | /eCare/de/home | /masquerade?locale= | fr     |