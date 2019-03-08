Feature: Superadmin can add a tax rate
  In order to add a new tax rate
  As a superadmin
  I want to add a tax rate filling a form

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"
    When I visit "/tax-rates/new"

  Scenario: I can add a tax rate
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a tax rate
    Given I fill the "taxRate" fields with:
      | amount               | 22      |
      | translations.en.name | VAT 22% |
      | translations.it.name | IVA 22% |
    When I press "Save and close"
    Then I should be on "/tax-rates"
    And I should see 1 "tax-rate"

  Scenario: I cannot add a tax rate without amount
    When I press "Save and close"
    Then I should be on "/tax-rates/new"
    And I should see a "Empty amount" error
