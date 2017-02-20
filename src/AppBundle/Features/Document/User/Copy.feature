Feature: User copies a document
  In order to create a copy of a document
  As a user
  I want to duplicate all its fields

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a customer:
      | name     | email                | company |
      | Customer | customer@example.com | Acme    |
    And there are tax rates:
      | name | amount |
      | 10%  | 10     |
      | 20%  | 20     |
    And there is a quote:
      | user             | customer             | ref | year | company |
      | user@example.com | customer@example.com | D01 | 2014 | Acme    |
    And there are document rows:
      | document | title     | unitPrice | quantity | taxRate |
      | D01      | Product 1 | 100       | 1        | 10      |
      | D01      | Product 2 | 200       | 2        | 20      |
    And I am logged as "user@example.com"
    And I visit "/documents/%documents.D01.id%/copy"

  Scenario: I see the copied document details
    Then I should see the "document" details:
      | net-total   | 500.00 |
      | taxes-1000  | 10.00  |
      | taxes-2000  | 80.00  |
      | gross-total | 590.00 |

  Scenario: I see the copied document
    When I visit "/quotes"
    Then I should be on "/quotes"
    And I should see 2 "document"s
