Feature: Account

  Background:
   Given the browser is set to size

  @javascript @account
  Scenario Outline: Sign in to user account
    When "<User>" is on the account page
    And I am on "https://mywingo.wingo.ch/masquerade?locale=fr"
    Then user fills in the account form
    And I press "<Button>"
    Then I should be redirected to "<Url>"

    Examples:
      | User  | Button             | Url                                    |
      | gabin | Accéder à my Wingo | https://mywingo.wingo.ch/eCare/de/home |