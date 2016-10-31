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

  @javascript @product
  Scenario Outline: Check homepage products content
    When I am on "/"
    And I should see "Ton opérateur sans embrouille"
    Then I click button of a "<Colour>" product
    And I should be redirected to "<Url>"

    Examples:
      | Colour | Url       |
      | green  | /mobile   |
      | blue   | /internet |
