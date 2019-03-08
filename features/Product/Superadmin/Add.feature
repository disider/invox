Feature: Superadmin can add a product
  In order to add a new product
  As an superadmin
  I want to add a product filling a form for any company

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
    When I visit "/products/new"

  Scenario: I can add a product
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a product
    Given I fill the "product" fields with:
      | name      | Product1 |
      | code      | PR1      |
      | unitPrice | 10       |
    And I select the "product.company" option "Acme"
    When I press "Save and close"
    Then I should be on "/products"
    And I should see 1 "product"
