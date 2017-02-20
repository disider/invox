Feature: User searches a document
  In order to search for a document
  As a user
  I want to search for documents

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are accounts:
      | name  | company |
      | Bank1 | Acme    |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    Given there are quotes:
      | ref | user              | customer              | company | title        |
      | 1   | user1@example.com | customer1@example.com | Acme    | First quote  |
      | 2   | user1@example.com | customer1@example.com | Acme    | Second quote |
    And there are invoices:
      | ref | user              | customer              | company | direction |
      | 3   | user1@example.com | customer1@example.com | Acme    | outgoing  |
      | 4   | user1@example.com | customer1@example.com | Acme    | incoming  |
    And there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And I am logged as "user1@example.com"

  Scenario: I can generate a new protocol number for invoices
    When I visit "/documents/generate-ref?type=invoice"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "ref" property should equal "4"

  Scenario: I can generate a new protocol number for quotes
    When I visit "/documents/generate-ref?type=quote"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "ref" property should equal "3"

  Scenario: I can generate a new protocol number for invoices by year
    When I visit "/documents/generate-ref?type=invoice&year=2099"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "ref" property should equal "1"
