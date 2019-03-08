Feature: User can edit a working note

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

  Scenario: I can edit a working note
    Given I visit "/working-notes/%workingNotes.last.id%/edit"
    When I fill the "workingNote" fields with:
      | title | title0 |
      | code  | code1  |
    And I press "Save"
    Then I should be on "/working-notes/%workingNotes.last.id%/edit"
    And I should see the "workingNote" fields:
      | title | title0 |
      | code  | code1  |
    And I should see "successfully updated"

  Scenario: I cannot edit an undefined working note
    When I try to visit "/working-notes/0/edit"
    Then the response status code should be 404