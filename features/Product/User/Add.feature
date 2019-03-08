Feature: User can add a product
  In order to add a new product
  As a user
  I want to add a product filling a form

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "user@example.com"
    When I visit "/products/new"

  Scenario: I can add a product
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a product
    Given I fill the "product" fields with:
      | name      | Product1 |
      | code      | PR1      |
      | unitPrice | 10       |
    When I press "Save and close"
    Then I should be on "/products"
    And I should see 1 "product"
    And I should see the "product" rows:
      | name     | code |
      | Product1 | PR1  |

  Scenario: I cannot add a product without name
    When I press "Save and close"
    Then I should be on "/products/new"
    And I should see a "Empty name" error

  Scenario: I cannot add a product to the warehouse if the warehouse is disabled
    Given there is a company:
      | name  | owner            | modules  |
      | Acme1 | user@example.com | products |
    And I visit "/companies/%companies.Acme1.id%/select"
    When I visit "/products/new"
    Then I should not see the "product" fields:
      | enabledInWarehouse |
      | initialStock       |
      | currentStock       |
