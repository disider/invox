Feature: Superadmin adds a customer
  In order to add a customer for a company
  As a superadmin
  I want to add a customer filling a form assigning the company

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a country:
      | code |
      | IT   |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"
    When I visit "/customers/new"

  Scenario: I can add a customer to any company
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a customer
    Given I fill the "customer" form with:
      | name           | email             | vatNumber   |
      | First customer | first@example.com | 01234567890 |
    And I select the "customer.company" option "Acme"
    When I press "Save and close"
    Then I should be on "/customers"
    And I should see 1 "customer"

  Scenario: I cannot add a customer without mandatory details
    When I press "Save and close"
    Then I should be on "/customers/new"
    And I should see an "Empty name" error
