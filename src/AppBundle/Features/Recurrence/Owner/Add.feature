Feature: Owner can add a recurrences
  In order to add a new recurrence
  As an owner
  I want to add a recurrence filling a form

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a customer:
      | name | email                | company |
      | C1   | customer@example.com | Acme    |
    And I am logged as "user@example.com"
    When I visit "/recurrences/new"

  Scenario: I add a recurrence
    Given I fill the "recurrence" fields with:
      | content     | R1                  |
      | customer    | %customers.last.id% |
      | startAt     | 12/12/2016          |
      | repeatEvery | 1                   |
      | amount      | 10                  |
    And I select the "recurrence.direction" option "outgoing"
    When I press "Save and close"
    Then I should be on "/recurrences"
    And I should see 1 "recurrence"
    And I should see the "recurrence" rows:
      | direction | content |
      | Outgoing  | R1      |

  Scenario: I cannot add a recurrence without required fields
    Given I fill the "recurrence" fields with:
      | repeatEvery |  |
    When I press "Save and close"
    Then I should be on "/recurrences/new"
    And I should see a "Empty content" error
    And I should see a "Empty direction" error
    And I should see a "Empty start at" error
    And I should see a "Empty repeat every" error
    And I should see a "Empty amount" error
