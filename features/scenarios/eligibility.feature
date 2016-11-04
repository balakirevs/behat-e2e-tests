Feature: Check eligibility

  Background:
    Given the browser is set to size

  @javascript @eligibility
  Scenario Outline: Check eligibility
    Then I am on "/<locale>"
    And I should see "NO BULLSHIT"
    Then I click button of a "<Colour>" product
    And I should be redirected to "<locale><Url>"
    And I fill in the eligibility form with
      | NPA         | Street        | Number |
      | 1205 Genève | rue des Bains | 35     |
    When I click the verification button
    Then I should see a spinner running
    And I should see the message of the product availability "<Message>"
    Then I click checkout button
    And I should be redirected to "https://jerry-preprod.wingo.ch/checkouts?l=<locale>"
    When I check the "TV" checkbox of the "<Product>"
    And I press "<ButtonNext>"
    Then I should be switched to the next step "<ContactStep>"
    And I fill in the content form with random credentials
      | Title     | Field                          |
      | "<Title>" | checkout_service_address_title |
    And I press "<ButtonNext>"
    Then I should be switched to the next step "<OrderStep>"
    And I check radio button with "<Text>"
    Then I check "<AcceptConditions>" checkbox
    When I press "<ButtonOrder>"
    Then I should be redirected to "https://jerry-preprod.wingo.ch/checkouts?l=<locale>&done=true"
    And I should see "<MessageThank>"

    Examples:
      | Product | Text                      | Colour | Url       | locale | Message                                            | ButtonNext | ContactStep     | Title    | OrderStep        | AcceptConditions                       | ButtonOrder         | MessageThank             |
      | IPTV    | Je n’ai pas de numéro OTO | blue   | /internet | fr     | Cool! Nos produits sont disponibles chez toi.      | Suivant    | Tes coordonnées | Monsieur | Ta commande      | Accepter les conditions contractuelles | Passer la commande  | Merci pour ton achat     |
      | IPTV    | Ich habe keine OTO-Nummer | blue   | /internet | de     | Very nice! Unsere Produkte sind bei dir verfügbar. | Weiter     | Deine Angaben   | Herr     | Deine Bestellung | Vertragsbedingungen akzeptieren        | Jetzt kaufen        | Danke für deinen Einkauf |