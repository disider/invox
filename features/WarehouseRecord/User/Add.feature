Feature: User can add a warehouse record
  In order to update product stocks
  As a user
  I want to insert a new warehouse record

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
    And I am logged as "user1@example.com"

  Scenario: I add a warehouse record
    When I visit "/products/%products.AcmeProduct1.id%/warehouse"
    And I fill the "warehouseRecord" fields with:
      | loadQuantity   | 10 |
      | unloadQuantity | 5  |
      | purchasePrice  | 5  |
      | sellPrice      | 10 |
    And I press "Add"
    Then I should see the "record" rows:
      | stock | load-quantity | unload-quantity |
      | 105   | 10            | 5               |
      | 100   | &ndash;       | &ndash;         |

  Scenario: I see the updated product stock
    When I visit "/products/%products.AcmeProduct1.id%/warehouse"
    And I fill the "warehouseRecord" fields with:
      | loadQuantity   | 10 |
      | unloadQuantity | 5  |
      | purchasePrice  | 5  |
      | sellPrice      | 10 |
    And I press "Add"
    And I visit "/products/%products.AcmeProduct1.id%/edit"
    Then I should see the "product.currentStock" field with "105.00"

  Scenario: I see validation errors for missing fields
    When I visit "/products/%products.AcmeProduct1.id%/warehouse"
    And I press "Add"
    Then I should see a "Insert a load or unload quantity" global error

  Scenario: I see validation errors for missing fields
    When I visit "/products/%products.AcmeProduct1.id%/warehouse"
    And I fill the "warehouseRecord" fields with:
      | loadQuantity   | 10 |
      | unloadQuantity | 10 |
    And I press "Add"
    Then I should see the "warehouseRecord" form errors:
      | purchasePrice | Empty price |
      | sellPrice     | Empty price |

  Scenario: I see validation errors for invalid fields
    When I visit "/products/%products.AcmeProduct1.id%/warehouse"
    And I fill the "warehouseRecord" fields with:
      | loadQuantity   | ABC |
      | unloadQuantity | ABC |
      | purchasePrice  | ABC |
      | sellPrice      | ABC |
    And I press "Add"
    Then I should see the "warehouseRecord" form errors:
      | loadQuantity   | Invalid quantity |
      | unloadQuantity | Invalid quantity |
      | purchasePrice  | Invalid price    |
      | sellPrice      | Invalid price    |
