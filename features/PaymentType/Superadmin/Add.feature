Feature: Superadmin can add a payment type
  In order to add a new payment type
  As a superadmin
  I want to add a payment type filling a form

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"
    When I visit "/payment-types/new"

  Scenario: I can add a payment type
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a payment type
    Given I fill the "paymentType" fields with:
      | days                 | 30                        |
      | translations.en.name | 30 days from invoice date |
      | translations.it.name | 30gg data fattura         |
    And I check the "paymentType.endOfMonth" field
    When I press "Save and close"
    Then I should be on "/payment-types"
    And I should see 1 "payment-type"

  Scenario: I cannot add a payment type without amount
    When I press "Save and close"
    Then I should be on "/payment-types/new"
    And I should see a "Empty name" error
