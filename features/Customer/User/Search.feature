Feature: User searches a customer
  In order to search for a customer
  As a user
  I want to search for customers

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And I am logged as "user1@example.com"

  Scenario: I can search for a customer
    When I visit "/customers/search?term=cust"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "customers" property should contain 1 item
