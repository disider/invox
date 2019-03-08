Feature: Superadmin can delete a tax rate
  In order to delete a tax rate
  As a superadmin
  I want to delete a tax rate

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a tax rate:
      | amount |
      | 22     |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a tax rate
    When I visit "/tax-rates/%taxRates.last.id%/delete"
    Then I should be on "/tax-rates"
    And I should see 0 "tax-rate"
