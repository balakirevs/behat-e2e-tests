Feature: Account

  Background:
   Given the browser is set to size

  @javascript @account
  Scenario Outline: Sign in to user account
    And "<User>" exists in a system
    When I am on "<AccountUrl>"
    Then I fill in the account form
    And I press "<Button>"
    Then I should be redirected to "<eCareUrl>"

    Examples:
      | User  | Button             | eCareUrl                               | AccountUrl                                    |
      | gabin | Accéder à my Wingo | https://mywingo.wingo.ch/eCare/de/home | https://mywingo.wingo.ch/masquerade?locale=fr |