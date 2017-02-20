Feature: Superadmin can list all his products
  In order to view his products
  As a superadmin
  I want to view the list of all products

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

  Scenario: I can add new products
    When I visit "/products"
    And I should see the "/products/new" link

  Scenario: I view all products
    Given there is a product:
      | name     | company |
      | Product1 | Acme    |
    When I visit "/products"
    Then I should see 1 "product"
    And I should see the "/products/new" link
