Feature: Owner can edit a recurrence
  In order to modify a recurrence
  As an owner
  I want to edit recurrence details

  Background:
    Given there are users:
      | email            | password |
      | user@example.com | secret   |
      | user1@example.com | secret   |
    And there are companies:
      | name | owner            |
      | Acme | user@example.com |
      | Bros | user1@example.com |
    And there are customers:
      | name | email                | company |
      | C1   | customer@example.com | Acme    |
      | C2   | customer@example.com | Bros    |
    And there is a recurrence:
      | content | customer | company |
      | R1      | C1       | Acme    |
    And I am logged as "user@example.com"

  Scenario: I can view a recurrence
    When I visit "/recurrences/%recurrences.last.id%/edit"
    Then I should see the "recurrence" fields:
      | content | R1 |

  Scenario: I can update a recurrence
    When I visit "/recurrences/%recurrences.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a recurrence
    When I visit "/recurrences/%recurrences.last.id%/edit"
    And I fill the "recurrence" fields with:
      | content | R11 |
    And I press "Save and close"
    Then I should be on "/recurrences"
    And I should see "R11"

  Scenario: I cannot update a recurrence I don't own
    Given there is a recurrence:
      | content | customer | company |
      | R1      | C2       | Bros    |
    When I try to visit "/recurrences/%recurrences.last.id%/edit"
    Then the response status code should be 403

  Scenario: I cannot edit an undefined recurrence
    When I try to visit "/recurrences/0/edit"
    Then the response status code should be 404
