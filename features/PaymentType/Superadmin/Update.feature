Feature: Superadmin can edit a payment type
  In order to modify a payment type
  As a superadmin
  I want to edit payment type details

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a payment type:
      | name                 | days | endOfMonth |
      | 30 days end of month | 30   | true       |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a payment type details
    When I visit "/payment-types/%paymentTypes.last.id%/edit"
    Then I should see the "paymentType" fields:
      | days                 | 30                   |
      | translations.en.name | 30 days end of month |
    And I should see the "paymentType.endOfMonth" field checked

  Scenario: I can update a payment type
    When I visit "/payment-types/%paymentTypes.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a payment type
    When I visit "/payment-types/%paymentTypes.last.id%/edit"
    And I fill the "paymentType" fields with:
      | days                 | 30                   |
      | translations.en.name | 30 days end of month |
      | translations.it.name | 30gg fine mese       |
    And I press "Save and close"
    Then I should be on "/payment-types"
    And I should see "30 days end of month"

  Scenario: I cannot edit an undefined payment type
    When I try to visit "/payment-types/0/edit"
    Then the response status code should be 404
