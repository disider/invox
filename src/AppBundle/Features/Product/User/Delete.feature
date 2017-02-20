Feature: User can delete a product
  In order to delete a product
  As a user
  I want to delete a product

  Background:
    Given there is a user:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I delete a product
    Given there is a product:
      | name     | company |
      | Product1 | Acme    |
    When I visit "/products/%products.Product1.id%/delete"
    Then I should be on "/products"
    And I should see 0 "product"

  Scenario: I cannot delete a product I don't own
    Given there is a product:
      | name             | company |
      | NotOwnedProduct1 | Bros    |
    When I try to visit "/products/%products.NotOwnedProduct1.id%/delete"
    Then the response status code should be 403
