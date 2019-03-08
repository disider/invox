Feature: User views stats about his documents
  In order to view my company stats
  As a user
  I want to view all document stats

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name  | owner             |
      | Acme  | user1@example.com |
      | Bros1 | user2@example.com |
      | Bros2 | user2@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros1   |
    And there is a quote:
      | customer              | company | ref | year | validUntil |
      | customer1@example.com | Acme    | Q01 | 2014 | 01/01/2014 |
    And there are invoices:
      | customer              | company | ref | year | validUntil | direction |
      | customer1@example.com | Acme    | I01 | 2014 | 01/01/2014 | incoming  |
      | customer1@example.com | Acme    | I02 | 2014 | 01/01/2014 | outgoing  |
      | customer1@example.com | Acme    | I03 | 2014 | 01/01/2099 | outgoing  |
      | customer2@example.com | Bros1   | I11 | 2014 | 01/01/2014 | outgoing  |
    And there are tax rates:
      | name | amount |
      | 0%   | 00     |
    And there are document rows:
      | document | title     | unitPrice |
      | I01      | Product 1 | 1.00      |
      | I02      | Product 2 | 2.00      |
      | I03      | Product 3 | 3.00      |

  Scenario: I view only expired invoices
    Given I am logged as "user1@example.com"
    When I visit "/"
    Then I should see 2 "invoice" rows
    Then I should see 1 "incoming" rows
    Then I should see 1 "outgoing" rows

  Scenario: I view expired invoices
    Given there is an account:
      | name | company |
      | Bank | Acme    |
    And there is a petty cash note:
      | ref | accountFrom | amount |
      | PC1 | Bank        | 1.00   |
    And there is an invoice linked to a petty cash note:
      | invoice | note | amount |
      | I01     | PC1  | 1.00   |
    And I am logged as "user1@example.com"
    When I visit "/"
    Then I should see 1 "invoice" rows

  Scenario: I view no stats if no current company is selected
    Given I am logged as "user2@example.com"
    When I visit "/"
    Then I should see no "invoice" rows
    And I should see "Welcome to Invox"
