Feature: User filters his documents
  In order to view only selected documents
  As a user
  I want to filter my documents

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
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
      | ref | user             | customer              | company | issuedAt     | validUntil   |
      | I01 | user@example.com | customer1@example.com | Acme    | now -1 month | now +1 month |
      | I02 | user@example.com | customer2@example.com | Acme    | now -1 day   | now +1 day   |
    And there is a document row:
      | document | title     | unitPrice |
      | I01      | Product 1 | 100       |
    And I am logged as "user@example.com"
    And I visit "/invoices"

  Scenario: I can sort the documents
    Then I should see the "/invoices?sort=document.ref&direction=asc&page=1" link
    And I should see the "/invoices?sort=document.issuedAt&direction=asc&page=1" link
    And I should see the "document" rows:
      | type    | ref    |
      | Invoice | I01/%date('y')% |
      | Invoice | I02/%date('y')% |
