Feature: Owner filters his petty cash notes
  In order to view my petty cash notes details
  As a user
  I want to filter my petty cash notes

  Background:
    Given there are users:
      | email              |
      | owner1@example.com |
      | owner2@example.com |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there are accounts:
      | name  | company |
      | Bank1 | Acme    |
      | Bank2 | Bros    |
    And there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there are invoices:
      | ref | user               | customer              | company |
      | I11 | owner1@example.com | customer1@example.com | Acme    |
      | I21 | owner2@example.com | customer2@example.com | Bros    |
    And there are document rows:
      | document | title     | unitPrice | taxRate |
      | I11      | Product 1 | 5         | 0       |
      | I21      | Product   | 10        | 0       |
    And there are petty cash notes:
      | ref | accountFrom | amount | recordedAt |
      | PC1 | Bank1       | 10.00  | now        |
      | PC2 | Bank1       | 10.00  | now -2     |
    And there are invoices linked to petty cash notes:
      | invoice | note | amount |
      | I11     | PC1  | 5.00   |
      | I21     | PC2  | 5.00   |
    And I am logged as "owner1@example.com"
    And I visit "/petty-cash-notes"

  Scenario: I can filter the petty cash notes
    Then I can press "Filter"

  Scenario: I filter the petty cash notes by customer
    Given I fill the "pettyCashNoteFilter[customer]" field with "Customer1"
    When I press "Filter"
    And I should see 1 "petty-cash-note"

  Scenario: I filter the petty cash note by type
    Given  I select the "pettyCashNoteFilter[type]" options:
      | Outcome |
    When I press "Filter"
    Then I should see 2 "outcome"s

  Scenario Outline: I filter the petty cash notes by recorded at (from)
    Given I fill the "pettyCashNoteFilter[recordedAt][left_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <petty-cash-note> "petty-cash-note"s

    Examples:
      | date        | petty-cash-note |
      | now         | 2               |
      | now +2 days | 0               |
      | now -2 days | 2               |

  Scenario Outline: I filter the petty cash notes by recorded at (to)
    Given I fill the "pettyCashNoteFilter[recordedAt][right_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <petty-cash-note> "petty-cash-note"s

    Examples:
      | date        | petty-cash-note |
      | now         | 2               |
      | now +2 days | 2               |
      | now -2 days | 0               |
