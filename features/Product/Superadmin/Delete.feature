Feature: Superadmin can delete any product
  In order to delete a product
  As a superadmin
  I want to delete a product

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a product:
      | name     | company |
      | Product1 | Acme    |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I can delete any product
    When I visit "/products/%products.Product1.id%/delete"
    Then I should be on "/products"
    And I should see 0 "product"
