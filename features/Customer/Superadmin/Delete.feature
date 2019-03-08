Feature: Superadmin can delete any customer
  In order to delete a customer
  As a superadmin
  I want to delete any customer

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I delete a customer
    Given there is a customer:
      | name     | email                | company |
      | Customer | customer@example.com | Acme    |
    When I visit "/customers/%customers.Customer.id%/delete"
    Then I should be on "/customers"
    And I should see 0 "customer"
