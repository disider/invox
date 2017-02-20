Feature: User can list all his products
  In order to view his products
  As a user
  I want to view the list of all my products

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I can add new products
    When I visit "/products"
    Then I should see the "/products/new" link

  Scenario: I view all my products
    Given there is a product:
      | name     | company |
      | Product1 | Acme    |
    When I visit "/products"
    Then I should see 1 "product"

  Scenario: I view the products paginated
    Given there are products:
      | name      | company |
      | Product 1 | Acme    |
      | Product 2 | Acme    |
      | Product 3 | Acme    |
      | Product 4 | Acme    |
      | Product 5 | Acme    |
      | Product 6 | Acme    |
    When I am on "/products"
    Then I should see 5 "product"
    When I am on "/products?page=2"
    Then I should see 1 "product"
    When I am on "/products?page=3"
    Then I should see 0 "product"

  Scenario: I can handle products
    Given there are products:
      | name      | company | enabledInWarehouse |
      | Product 1 | Acme    | true               |
      | Product 2 | Acme    | false              |
    When I visit "/products"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".warehouse"
    And I should see 1 link with class ".create"

  Scenario: I view no products I don't own
    Given there are products:
      | name      | company |
      | Product 1 | Bros    |
      | Product 2 | Bros    |
    When I visit "/products"
    Then I should see 0 "product"

  Scenario: I cannot view a warehouse if warehouse module is not enabled
    Given there is a company:
      | name  | owner             | modules  |
      | Acme1 | user1@example.com | products |
    And I visit "/companies/%companies.Acme1.id%/select"
    Given there are products:
      | name      | company | enabledInWarehouse |
      | Product 1 | Acme1   | true               |
      | Product 2 | Acme1   | false              |
    When I visit "/products"
    Then I should see 0 links with class ".warehouse"

  Scenario: I can filter product by code
    Given there are products:
      | name            | code | company |
      | Product 1       | 001  | Acme    |
      | Other Product 2 | 002  | Acme    |
    When I visit "/products"
    When I fill the "productFilter.code" field with "001"
    And I press "Filter"
    Then I should be on "/products"
    And I should see 1 "product"
    And I should see "Product 1"

  Scenario: I can filter product by name
    Given there are products:
      | name            | code | company |
      | Product 1       | 001  | Acme    |
      | Other Product 2 | 002  | Acme    |
    When I visit "/products"
    When I fill the "productFilter.name" field with "Other Product"
    And I press "Filter"
    Then I should be on "/products"
    And I should see 1 "product"
    And I should see "Other Product 2"
