Feature: User views a working note
  In order to view a working note
  As a user
  I want to view all working note details

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

  Scenario: I can view a working note details
    When I visit "/working-notes/%workingNotes.last.id%/render?showAsHtml=true"
    Then I should see the "working-note" details:
      | code  | CODE1 |
