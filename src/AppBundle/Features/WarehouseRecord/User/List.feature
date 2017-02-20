Feature: User can list all his warehouse records
  In order to view his product stocks
  As a user
  I want to view the list of all my products in the warehouse

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
      | AcmeProduct2 | Acme    | false              | 0            |
      | BrosProduct1 | Bros    | true               | 200          |
    And there are warehouse records:
      | product      | loadQuantity | unloadQuantity | purchasePrice | sellPrice |
      | AcmeProduct1 | 10           | 5              | 5             | 10        |
    And I am logged as "user1@example.com"

  Scenario: I can see my products in warehouse
    When I visit "/products/%products.AcmeProduct1.id%/warehouse"
    Then I should see the "record" rows:
      | stock | load-quantity | unload-quantity |
      | 105   | 10            | 5               |
      | 100   | &ndash;       | &ndash;         |
