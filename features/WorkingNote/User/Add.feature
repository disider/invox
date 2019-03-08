Feature: User creates a working note

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a customer:
      | name      | email                 | company | language |
      | Customer1 | customer1@example.com | Acme    | it       |
    And I am logged as "user@example.com"

  Scenario: I create a working note
    Given I visit "/working-notes/new"
    When I fill the "workingNote" fields with:
      | title    | title0              |
      | code     | code1               |
      | customer | %customers.last.id% |
    And I press "Save"
    Then I should be on "/working-notes/%workingNotes.last.id%/edit"
