Feature: Superadmin can edit a product
  In order to modify a product
  As a superadmin
  I want to edit product details

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

  Scenario: I can view a product details
    When I visit "/products/%products.Product1.id%/edit"
    Then I should see the "product" fields:
      | name | Product1 |

  Scenario: I can update a product
    When I visit "/products/%products.Product1.id%/edit"
    Then I can press "Save"

  Scenario: I update a product
    When I visit "/products/%products.Product1.id%/edit"
    And I fill the "product" fields with:
      | name | New name |
    And I press "Save and close"
    Then I should be on "/products"
    And I should see "New name"

  Scenario: I cannot edit an undefined product
    When I try to visit "/products/0/edit"
    Then the response status code should be 404
