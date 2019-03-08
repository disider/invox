Feature: User lists his invoices
  In order to access my invoice details
  As a user
  I can list all my invoices

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
    And there is a quote:
      | ref | customer              | company |
      | 001 | customer1@example.com | Acme    |
    And I am logged as "user1@example.com"

  Scenario: I can add new invoices
    When I visit "/invoices"
    Then I should see the "/documents/new?type=invoice" link

  Scenario: I view a list of invoices
    Given there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And there are invoices:
      | ref | customer              | company | direction |
      | I01 | customer1@example.com | Acme    | incoming  |
      | I02 | customer1@example.com | Acme    | outgoing  |
    And there is a document row:
      | document | title     | unitPrice |
      | I01      | Product 1 | 100       |
    When I visit "/invoices"
    Then I should see the "document" rows:
      | type    | ref             |
      | Invoice | I01             |
      | Invoice | I02/%date('y')% |
    And I should see the "document.0" row details:
      | direction | Incoming |
    And I should see the "document.1" row details:
      | direction | Outgoing |
    And I should see the "totals" rows:
      | unpaid-total | paid-total | total  |
      | 100.00       | 0.00       | 100.00 |

  Scenario: I view the invoices paginated
    Given there are invoices:
      | ref | customer              | year | company | direction |
      | 001 | customer1@example.com | 2014 | Acme    | incoming  |
      | 002 | customer1@example.com | 2014 | Acme    | incoming  |
      | 003 | customer1@example.com | 2014 | Acme    | incoming  |
      | 004 | customer1@example.com | 2014 | Acme    | incoming  |
      | 005 | customer1@example.com | 2014 | Acme    | incoming  |
      | 006 | customer1@example.com | 2014 | Acme    | incoming  |
    When I am on "/invoices"
    Then I should see 5 "document"
    When I am on "/invoices?page=2"
    Then I should see 1 "document"
    When I am on "/invoices?page=3"
    Then I should see 0 "document"

  Scenario: I can handle invoices
    Given there are invoices:
      | customer              | ref | year | company |
      | customer1@example.com | 001 | 2014 | Acme    |
      | customer1@example.com | 002 | 2014 | Acme    |
    When I visit "/invoices"
    Then I should see 2 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 2 links with class ".copy"
    And I should see 2 links with class ".view"
    And I should see 1 link with class ".create"

  Scenario: I view no invoices I don't own
    Given there is an invoice:
      | customer              | ref | year | company |
      | customer2@example.com | 001 | 2014 | Bros    |
    When I visit "/invoices"
    Then I should see 0 "document"

  Scenario: I view no invoices for a company I did not select
    Given there is a company:
      | name           | owner             |
      | AnotherCompany | user1@example.com |
    And there is an invoice:
      | customer              | ref | year | company        |
      | customer1@example.com | 001 | 2014 | AnotherCompany |
    And I visit "/companies/%companies.Acme.id%/select"
    When I visit "/invoices"
    Then I should see 0 "document"
