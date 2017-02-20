Feature: User searches a recurrence
  In order to search for a recurrence
  As a user
  I want to search for recurrences

  Background:
    Given there are users:
      | email             | password |
      | user@example.com  | secret   |
      | user1@example.com | secret   |
    And there are companies:
      | name | owner             |
      | Acme | user@example.com  |
      | Bros | user1@example.com |
    And there are customers:
      | name | email                 | company |
      | C1   | customer1@example.com | Acme    |
      | C2   | customer2@example.com | Bros    |
    And there are recurrences:
      | content      | customer | company |
      | Recurrence 1 | C1       | Acme    |
      | Recurrence 2 | C1       | Acme    |
      | Recurrence 3 | C2       | Acme    |
    And I am logged as "user1@example.com"

  Scenario: I can search for a recurrence
    When I visit "/recurrences/search?customerId=%customers.C1.id%&term=recurre"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "recurrences" property should contain 2 item

  Scenario: I can retrieve only not finished recurrences
    Given there is a recurrence:
      | content      | customer | company | occurrences |
      | Recurrence 4 | C1       | Acme    | 1           |
    And there is an invoice:
      | customer              | company | ref | recurrence   |
      | customer1@example.com | Acme    | I01 | Recurrence 4 |
    When I visit "/recurrences/search?customerId=%customers.C1.id%&term=recurre"
    Then the "recurrences" property should contain 2 item