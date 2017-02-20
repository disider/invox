Feature: Superadmin lists all customers
  In order to access all customer details
  As a user
  I can list all customers

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there are customers:
      | name       | email              | company |
      | Customer 1 | customer1@acme.com | Acme    |
      | Customer 2 | customer2@acme.com | Acme    |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I view a list of all customers
    When I visit "/customers"
    Then I should see 2 "customer"
