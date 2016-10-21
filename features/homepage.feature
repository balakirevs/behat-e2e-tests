Feature: Homepage Content

  @javascript @home
  Scenario: Check homepage content
    When I am on "/"
    Then I should see "Wingo Internet"
    When I click menu icon
    Then I should see "Home"
    When I click "Fix" link
    And I should be redirected to "/telephonie-fixe"
    Then I should see eligibility form container