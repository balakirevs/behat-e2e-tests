Feature: Check eligibility

  Background:
    Given the browser is set to size

  @javascript @eligibility
  Scenario Outline: Check eligibility
    Then I am on "/"
    And I fill in the eligibility form with
      | NPA         | Street        | Number |
      | 1205 Genève | rue des Bains | 35     |
    When I click the verification button
    Then I should see a spinner running
    And I should see the message of the product availability "Cool! Nos produits sont disponibles chez toi."
    Then I click checkout button
    And I should be redirected to "https://jerry-preprod.wingo.ch/checkouts?l=fr"
    When I check the "TV" checkbox of the "<Product>"
    And I press "Suivant"
    Then I should be switched to the next step "Tes coordonnées"
    And I fill in the content form with random credentials
      | Title    | Field                          |
      | Monsieur | checkout_service_address_title |
    And I press "Suivant"
    Then I should be switched to the next step "Ta commande"
    And I check radio button with "<Text>"
    Then I check "Accepter les conditions contractuelles" checkbox
    When I press "Passer la commande"
    Then I should be redirected to "https://jerry-preprod.wingo.ch/checkouts?l=fr&done=true"
    And I should see "Merci pour ton achat"

    Examples:
      | Product | Text                      |
      | IPTV    | Je n’ai pas de numéro OTO |