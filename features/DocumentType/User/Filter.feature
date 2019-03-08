Feature: User filters his invoices
  In order to view only selected invoices
  As a user
  I want to filter my invoices

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there are tax rates:
      | name | amount |
      | 0%   | 0      |
      | 10%  | 10     |
    And there is a company:
      | name | address         | vatNumber  | owner            |
      | Acme | Company address | 0123456789 | user@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Acme    |
    And there are invoices:
      | ref | user             | customer              | company | direction | issuedAt     | validUntil   | costCenters |
      | I01 | user@example.com | customer1@example.com | Acme    | incoming  | now -1 month | now +1 month | Expenses    |
      | I02 | user@example.com | customer2@example.com | Acme    | outgoing  | now -1 day   | now +1 day   |             |
    And there is a document row:
      | document | title     | unitPrice |
      | I01      | Product 1 | 100       |
    And I am logged as "user@example.com"
    And I visit "/invoices"

  Scenario: I can filter the invoices
    Then I can press "Filter"

  Scenario: I filter the invoices by ref
    Given I fill the "documentFilter[ref]" field with "1"
    When I press "Filter"
    And I should see 1 "invoice"
    And I should see 1 "document"s

  Scenario: I filter the invoices by direction
    Given I fill the "documentFilter[direction]" field with "incoming"
    When I press "Filter"
    Then I should see 1 "invoice"

  Scenario: I filter the invoices by customer
    Given I fill the "documentFilter[customer]" field with "Customer1"
    When I press "Filter"
    Then I should see 1 "invoice"

  Scenario: I filter the invoices by status
    Given I fill the "documentFilter[status]" field with "paid"
    When I press "Filter"
    Then I should see 1 "invoice"

  Scenario Outline: I filter the invoices by issue date (from)
    Given I fill the "documentFilter[issuedAt][left_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <invoices> "invoice"

    Examples:
      | date        | invoices |
      | now         | 0        |
      | now +2 days | 0        |
      | now -2 days | 1        |

  Scenario Outline: I filter the invoices by issue date (to)
    Given I fill the "documentFilter[issuedAt][right_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <invoices> "invoice"

    Examples:
      | date        | invoices |
      | now         | 2        |
      | now +2 days | 2        |
      | now -2 days | 1        |

  Scenario Outline: I filter the invoices by valid until (from)
    Given I fill the "documentFilter[validUntil][left_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    And print last response
    Then I should see <invoices> "invoice"

    Examples:
      | date          | invoices |
      | now           | 2        |
      | now +2 days   | 1        |
      | now +2 months | 0        |

  Scenario Outline: I filter the invoices by valid until (to)
    Given I fill the "documentFilter[validUntil][right_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <invoices> "invoice"

    Examples:
      | date          | invoices |
      | now           | 0        |
      | now +2 days   | 1        |
      | now +2 months | 2        |

  Scenario: I filter the invoices by cost center
    Given I fill the "documentFilter[costCenters]" field with "Expenses"
    When I press "Filter"
    Then I should see 1 "invoice"

  Scenario: I cannot see fields of other documents in quotes
    When I visit "/quotes"
    Then I should not see the "documentFilter_status" field
    And I should not see the "documentFilter_cost_centers" field
    And I should not see the "documentFilter_direction" field

  Scenario: I cannot see fields of other documents in orders
    When I visit "/orders"
    Then I should not see the "documentFilter_direction" field

  Scenario: I cannot see fields of other documents in credit notes
    When I visit "/credit-notes"
    Then I should not see the "documentFilter_direction" field
