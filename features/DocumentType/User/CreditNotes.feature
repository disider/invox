Feature: User lists his credit notes
  In order to access my credit note details
  As a user
  I can list all my credit notes

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

  Scenario: I can add new credit notes
    When I visit "/credit-notes"
    Then I should see the "/documents/new?type=credit_note" link

  Scenario: I view a list of credit notes
    Given there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And there are credit notes:
      | ref | customer              | company |
      | C01 | customer1@example.com | Acme    |
      | C02 | customer1@example.com | Acme    |
    And there is a document row:
      | document | title     | unitPrice |
      | C01      | Product 1 | 100       |
      | C02      | Product 2 | 50        |
    And there is an invoice:
      | ref | customer              | company | direction |
      | I01 | customer1@example.com | Acme    | incoming  |
    When I visit "/credit-notes"
    Then I should see 2 "document"
    And I should see the "document" rows:
      | type        | ref             |
      | Credit note | C01/%date('y')% |
      | Credit note | C02/%date('y')% |
    And I should see no "document.1" row details:
      | direction |
    And I should see no "unpaid-total" element
    And I should see no "paid-total" element
    And I should see the "totals" rows:
      | total  |
      | 150.00 |
