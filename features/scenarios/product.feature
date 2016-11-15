@product
Feature: Wingo products

  Background:
    Given the browser is set to size

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
      When I click "<Product>" link "<locale>"
      Then I should be redirected to "https://bbg-preprod.wingo.ch<locale>/checkout/onepage"
      And I check radio button for mobile with "<Text>"
      Then I select my mobile operator from the list
        | Operator     | Field                                     |
        | "<Operator>" | custom_step1_portability_current_provider |
      And I check the contract "<Type>" and portability "<Term>" according to "<Operator>"
      Then I fill a random phone number
      Then I enter date in the calendar
      And I press "<Button>"
      Then I must be switched to the next step "<DataStep>"
      And I fill in the mobile content form with random credentials
        | Title     | Field                   |
        | "<Title>" | custom_step2_user_title |
      Then I fill in my birthday form "<Date>"
      And I fill in the billing content form with random credentials
      When I fill in the eligibility billing form with
        | NPA  | City   | Street        | Number |
        | 1205 | Genève | rue des Bains | 35     |
      And I select "<Nationality>" from the list
      Then I select identity card "<IdCard>" from the list
      And I fill in identity card number "<Number>" according to the "<Nationality>" and type of "<IdCard>"
      And I fill in password fields "<Password>"
      Then I press button Continue
      And I must be switched to the next step "<OrderStep>"
      Then I accept Wingo conditions
      And I press "<ButtonOrder>"

    Examples:
    | Colour | Url     | locale | Text                                 | Operator           | Type              | Term                               | DataStep             | Title    | Date       | Nationality | Password  | IdCard                   | Number    | OrderStep             | ButtonOrder        | Product    | Button    |
    | green  | /mobile |  /fr   | Je souhaite garder mon numéro actuel | Salt (Orange)      | un abonnement     | Au terme de la durée contractuelle | Tes données          | Madame   | 12-05-1997 | Suisse      | Wingo2016 | Passeport                | 123456789 | Résumé de ta commande | Passer la commande | Commander  | Continuer |
    | green  | /mobile |  /fr   | Je souhaite garder mon numéro actuel | Lebara             | une carte prepaid | À une autre date                   | Tes données          | Monsieur | 12-11-1995 | France      | Wingo2016 | Permis de frontalier G   | 987654321 | Résumé de ta commande | Passer la commande | Commander  | Continuer |
    | green  | /mobile |  /de   | Ich möchte meine Rufnummer behalten  | Das Abo (Die Post) | ein Abonnement    | Auf ein anderes Datum              | Rechnungsinformation | Frau     | 07-07-1980 | Deutschland | Wingo2016 | Aufenthaltsbewilligung B | 234534551 | Bestellungsübersicht  | Jetzt kaufen       | Bestellen  | Weiter    |

  @javascript @newNumber
  Scenario Outline:
    Given I am on "/<locale>"
    Then I click button of a "<Colour>" product
    And I should be redirected to "/<locale><Url>"
    When I click "<Product>" link "<locale>"
    Then I should be redirected to "https://bbg-preprod.wingo.ch<locale>/checkout/onepage"
    And I check radio button for mobile with "<Text>"
    When I press "<Button>"
    Then I must be switched to the next step "<DataStep>"
    And I fill in the mobile content form with random credentials
      | Title     | Field                   |
      | "<Title>" | custom_step2_user_title |
    Then I fill in my birthday form "<Date>"
    And I fill in the billing content form with random credentials
    When I fill in the eligibility billing form with
      | NPA  | City   | Street        | Number |
      | 1205 | Genève | rue des Bains | 35     |
    And I select "<Nationality>" from the list
    Then I select identity card "<IdCard>" from the list
    And I fill in identity card number "<Number>" according to the "<Nationality>" and type of "<IdCard>"
    Then I fill in password fields "<Password>"
    #When I check checkbox of a minor user
    #And I fill in the mobile content form with random credentials
      | Title     | Field                            |
      | "<Title>" | custom_step2_user_title_underage |
    #Then I fill in my birthday form "<Date_2>"
    Then I press button Continue
    And I must be switched to the next step "<OrderStep>"
    Then I accept Wingo conditions
    And I press "<ButtonOrder>"

    Examples:
      | Colour | Url     | locale | Text                          | DataStep    | Title    | Date       | Date_2     |Nationality | Password  | IdCard    | Number    | OrderStep             | ButtonOrder        | Product    | Button    |
      | green  | /mobile |  /fr   | Je souhaite un nouveau numéro | Tes données | Madame   | 12-05-1997 | 02-02-2004 |Suisse      | Wingo2016 | Passeport | 123456789 | Résumé de ta commande | Passer la commande | Commander  | Continuer |