Feature: User searches a product
  In order to search for a product
  As a user
  I want to search for products

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             | modules  |
      | Acme | user1@example.com | products |
      | Bros | user2@example.com |          |
    Given there are products:
      | name | company |
      | PR1  | Acme    |
      | PR2  | Acme    |
      | PR3  | Bros    |

  Scenario: I can search for a product
    Given I am logged as "user1@example.com"
    When I visit "/products/search?term=PR"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "products" property should contain 2 items

  Scenario: I cannot search if product module is disabled
    Given I am logged as "user2@example.com"
    When I try to visit "/products/search?term=PR"
    Then the response status code should be 400
    And there should be response headers with:
      | Content-Type | application/problem+json |
    And the "products" property should not exist

  Scenario: I can search for a product
    Given I am logged as "user1@example.com"
    When I visit "/products/search?term=PR1"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "products" property should contain 1 item
