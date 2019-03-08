Feature: User prints a document
  In order to print a document
  As a user
  I want to print a PDF document

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a company:
      | name | address         | vatNumber  | owner            |
      | Acme | Company address | 0000000000 | user@example.com |
    And there is a customer:
      | name           | email                | company | address          | vatNumber |
      | First Customer | customer@example.com | Acme    | Customer address | 111111111 |
    And there is a quote:
      | user             | customer             | company | ref | year | issued_at  |
      | user@example.com | customer@example.com | Acme    | 001 | 2014 | 01/01/2014 |
    And I am logged as "user@example.com"

  Scenario: I can print a document
    When I visit "/documents/%documents.last.id%/print"
    Then there should be response headers with:
      | Content-Type    | Content-Disposition                                    |
      | application/pdf | attachment; filename="quote-001-14-first-customer.pdf" |
