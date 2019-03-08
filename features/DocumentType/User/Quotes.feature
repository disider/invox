Feature: User lists his quotes
  In order to access my quote details
  As a user
  I can list all my quotes

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

  Scenario: I can add new quotes
    When I visit "/quotes"
    Then I should see the "/documents/new?type=quote" link

  Scenario: I view a list of quotes
    Given there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And there are quotes:
      | ref | customer              | company |
      | 001 | customer1@example.com | Acme    |
      | 002 | customer1@example.com | Acme    |
    And there is a document row:
      | document | title     | unitPrice |
      | 001      | Product 1 | 100       |
      | 002      | Product 2 | 50        |
    And there is an invoice:
      | ref | customer              | company | direction |
      | I01 | customer1@example.com | Acme    | incoming  |
    When I visit "/quotes"
    Then I should see 2 "document"
    And I should see no "document.1" row details:
      | direction |
    And I should see no "unpaid-total" element
    And I should see no "paid-total" element
    And I should see the "totals" rows:
      | total  |
      | 150.00 |
