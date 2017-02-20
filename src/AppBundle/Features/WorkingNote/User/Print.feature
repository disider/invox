Feature: User prints a working note
  In order to print a working note
  As a user
  I want to print a PDF working note

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a company:
      | name | address         | vatNumber  | owner            |
      | Acme | Company address | 0000000000 | user@example.com |
    And there is a working note:
      | title | code  | company |
      | WN1   | CODE1 | Acme    |
    And I am logged as "user@example.com"

  Scenario: I can print a working-note
    When I visit "/working-notes/%workingNotes.last.id%/print"
    Then there should be response headers with:
      | Content-Type    | Content-Disposition                           |
      | application/pdf | attachment; filename="working-note-code1.pdf" |
