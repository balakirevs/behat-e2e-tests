@product
Feature: Wingo products

  @javascript @products
  Scenario Outline: Check homepage products content
    Given I am on "/<locale>"
    And I should see "NO BULLSHIT"
    Then I click button of a "<Colour>" product
    And I should be redirected to "/<locale><Url>"

    Examples:
    | Colour | Url       | locale |
    | green  | /mobile   |  /fr   |
    | blue   | /internet |  /fr   |

  @javascript @mobileProduct
    Scenario Outline:
      Given I am on "/<locale>"
      Then I click button of a "<Colour>" product
      And I should be redirected to "/<locale><Url>"
      When I click "Commander" link
      Then I should be redirected to "https://bbg-preprod.wingo.ch<locale>/checkout/onepage"
      And I check radio button for mobile with "<Text>"
      Then I select my mobile operator from the list
        | Operator     | Field                                     |
        | "<Operator>" | custom_step1_portability_current_provider |
      And I check radio button of the contract "<Type>"
      Then I fill a random phone number
      And I check radio button of the term duration "<Term>"
      Then I enter date in the calendar
      And I press "Continuer"
      Then I must be switched to the next step "<DataStep>"
      And I fill in the mobile content form with random credentials
        | Title     | Field                   |
        | "<Title>" | custom_step2_user_title |
      Then I fill in my birthday form

    Examples:
    | Colour | Url       | locale | Text                                 | Operator     | Type          | Term                               | DataStep    | Title  |
    | green  | /mobile   |  /fr   | Je souhaite garder mon numéro actuel | Salt (Orange)| un abonnement | Au terme de la durée contractuelle | Tes données | Madame |