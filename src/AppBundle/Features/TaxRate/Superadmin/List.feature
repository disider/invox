Feature: Superadmin can list all taxRates
  In order to view all taxRates
  As a superadmin
  I want to view the list of all taxRates

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"

  Scenario: I can add new taxRates
    When I visit "/tax-rates"
    Then I should see the "/tax-rates/new" link

  Scenario: I view all taxRates
    Given there is a tax rate:
      | amount |
      | 22     |
    When I visit "/tax-rates"
    Then I should see 1 "tax-rate"

  Scenario: I view the taxRates paginated
    Given there are tax rates:
      | name     | amount |
      | TaxRate1 | 10     |
      | TaxRate2 | 20     |
      | TaxRate3 | 30     |
      | TaxRate4 | 40     |
      | TaxRate5 | 50     |
      | TaxRate6 | 60     |
    When I am on "/tax-rates"
    Then I should see 5 "tax-rate"
    When I am on "/tax-rates?page=2"
    Then I should see 1 "tax-rate"
    When I am on "/tax-rates?page=3"
    Then I should see 0 "tax-rate"

  Scenario: I can handle taxRates
    Given there are tax rates:
      | name     | amount |
      | TaxRate1 | 10     |
      | TaxRate2 | 20     |
    When I visit "/tax-rates"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

