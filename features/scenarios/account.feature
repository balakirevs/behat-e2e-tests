Feature: Account

  Background:
   Given the browser is set to size
    Then A Domain is set to domain name "wingo" and domain "ch"
    And A Subdomain is set to "mywingo"

  @javascript @account
  Scenario Outline: Sign in to user account
    And "<User>" exists in a system
    When I am on "<AccountUrl>"
    Then I fill in the account form
    And I press "<Button>"
    Then I should be redirected to "<eCareUrl>"

    Examples:
      | User  | Button             | eCareUrl       | AccountUrl            |
      | gabin | Accéder à my Wingo | /eCare/de/home | /masquerade?locale=fr |