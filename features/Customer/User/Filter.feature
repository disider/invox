Feature: User filters his customers
  In order to view only my customers
  As a user
  I want to filter my customers

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    Given there are customers:
      | name       | email                 | company | vatNumber   |
      | First      | first@example.com     | Acme    | 00000000000 |
      | Customer 1 | customer1@example.com | Acme    | 11111111111 |
      | Customer 2 | customer2@example.com | Acme    | 22222222222 |
      | Customer 3 | customer3@example.com | Acme    | 33333333333 |
      | Customer 4 | customer4@example.com | Acme    | 44444444444 |
    And I am logged as "user1@example.com"
    And I am on "/customers"

  Scenario: I can filter the customers
    Then I can press "Filter"

  Scenario: I filter the customers by name
    Given I fill the "customerFilter[name]" field with "Customer"
    When I press "Filter"
    And I should see 4 "customer"s

  Scenario: I filter the customers by vat number
    Given I fill the "customerFilter[vatNumber]" field with "00000000000"
    When I press "Filter"
    And I should see 1 "customer"

