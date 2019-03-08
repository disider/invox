Feature: User can delete a working note

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a working note:
      | title  | code  | company |
      | title0 | code1 | Acme    |
    And I am logged as "user@example.com"

  Scenario: I can delete a working note
    When I visit "/working-notes/%workingNotes.last.id%/delete"
    Then I should be on "/working-notes"
    And I should see 0 "working-note"

  Scenario: I cannot delete an undefined working note
    When I try to visit "/working-notes/0/delete"
    Then the response status code should be 404