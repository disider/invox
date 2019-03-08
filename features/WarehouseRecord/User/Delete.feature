Feature: User can delete a warehouse record
  In order to update product stocks
  As a user
  I want to remove a warehouse record

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are products:
      | name         | company | enabledInWarehouse | initialStock |
      | AcmeProduct1 | Acme    | true               | 100          |
    And there are warehouse records:
      | product      | loadQuantity | unloadQuantity | purchasePrice | sellPrice |
      | AcmeProduct1 | 10           | 5              | 5             | 10        |
    And I am logged as "user1@example.com"

  Scenario: I delete a warehouse record
    When I visit "/products/%products.AcmeProduct1.id%/warehouse"
    And I click on "Delete"
    Then I should see the "record" rows:
      | stock | load-quantity | unload-quantity |
      | 100   | &ndash;       | &ndash;         |
    And I should see "successfully deleted"

  Scenario: I see the updated product stock
    Given I visit "/products/%products.AcmeProduct1.id%/warehouse"
    When I click on "Delete"
    And I visit "/products/%products.AcmeProduct1.id%/edit"
    Then I should see the "product.currentStock" field with "100.00"
