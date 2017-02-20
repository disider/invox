Feature: Accountant views a document
  In order to view a document
  As an accountant
  I want to view all document details

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a document template:
      | name         |
      | AcmeTemplate |
    And there is a company:
      | name | address         | vatNumber  | owner            |
      | Acme | Company address | 0123456789 | user@example.com |
    And there is a customer:
      | name     | email                | company | address          | vatNumber |
      | Customer | customer@example.com | Acme    | Customer address | 111111111 |
    And there are quotes:
      | user             | customer             | company | ref | year | issuedAt   | language |
      | user@example.com | customer@example.com | Acme    | D01 | 2014 | 01/01/2014 | en       |
      | user@example.com | customer@example.com | Acme    | D02 | 2014 | 01/01/2014 | it       |
    And there are tax rates:
      | name | amount |
      | 10%  | 10     |
      | 22%  | 22     |
    And there are document rows:
      | document | title     | unitPrice | quantity | taxRate |
      | D01      | Product 1 | 100       | 2        | 10      |
      | D01      | Product 2 | 200       | 4        | 22      |
    And I am logged as "user@example.com"

  Scenario: I can view a document details
    When I visit "/documents/%documents.D01.id%/render?showAsHtml=true"
    Then I should see the "document" details:
      | document-ref | D01/14 issued on 01/01/2014 |
    And I should see the "company" details:
      | name       | Acme            |
      | address    | Company address |
      | vat-number | 0123456789      |
    And I should see the "customer" details:
      | name       | Customer         |
      | address    | Customer address |
      | vat-number | 111111111        |
    And I should see the "row" rows:
      | title     | unit-price | quantity | net-cost | taxes  | gross-cost |
      | Product 1 | 100.00     | 2        | 200.00   | 20.00  | 220.00     |
      | Product 2 | 200.00     | 4        | 800.00   | 176.00 | 976.00     |
    And I should see the "rows .footer" details:
      | net-total   | 1,000.00 |
      | taxes-1000  | 20.00    |
      | taxes-2200  | 176.00   |
      | gross-total | 1,196.00 |
