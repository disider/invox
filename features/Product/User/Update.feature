Feature: User can edit a product
  In order to modify a product
  As a user
  I want to edit product details

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a tax rate:
      | name | amount |
      | 10%  | 10     |
    And there is a product:
      | name     | company | initialStock |
      | Product1 | Acme    | 0            |
    And I am logged as "user@example.com"

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
      | name          | New product name       |
      | code          | NC1                    |
      | measureUnit   | Liters                 |
      | taxRate       | %taxRates.last.id%     |
      | unitPrice     | 10                     |
      | tags          | ProductCategory1       |
      | description   | This is a nice product |
      | internalNotes | This will be secret    |
      | initialStock  | 10                     |
    And I check the "product.enabledInWarehouse" field
    And I press "Save"
    Then I should be on "/products/%products.NC1.id%/edit"
    And I should see "successfully updated"
    And I should see the "product" fields:
      | name          | New product name       |
      | code          | NC1                    |
      | measureUnit   | Liters                 |
      | taxRate       | %taxRates.last.id%     |
      | unitPrice     | 10.00                  |
      | tags          | ProductCategory1       |
      | description   | This is a nice product |
      | internalNotes | This will be secret    |
      | initialStock  | 10.00                  |
      | currentStock  | 10.00                  |
    And I should see the "product.enabledInWarehouse" field checked

  Scenario: I cannot edit an undefined product
    When I try to visit "/products/0/edit"
    Then the response status code should be 404
