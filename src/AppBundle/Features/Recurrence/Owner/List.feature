Feature: Owner can list all his recurrences
  In order to view his recurrences
  As an owner
  I want to view the list of all my recurrences

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name | email                | company |
      | C1   | customer@example.com | Acme    |
      | C2   | customer@example.com | Bros    |
    And I am logged as "user1@example.com"

  Scenario: I can add new recurrences
    When I visit "/recurrences"
    Then I should see the "/recurrences/new" link

  Scenario: I view all my recurrences
    Given there is a recurrence:
      | content | customer | company |
      | R1      | C1       | Acme    |
    When I visit "/recurrences"
    Then I should see 1 "recurrence"
    And I should see no "recurrences" details rows:
      | type        | outgoing            |
      | description | 10                  |
      | customer    | %customers.last.id% |

  Scenario: I view the recurrences paginated
    Given there are recurrences:
      | content | customer | company |
      | R1      | C1       | Acme    |
      | R2      | C1       | Acme    |
      | R3      | C1       | Acme    |
      | R4      | C1       | Acme    |
      | R5      | C1       | Acme    |
      | R6      | C1       | Acme    |
    When I am on "/recurrences"
    Then I should see 5 "recurrence"s
    When I am on "/recurrences?page=2"
    Then I should see 1 "recurrence"
    When I am on "/recurrences?page=3"
    Then I should see 0 "recurrence"

  Scenario: I can handle recurrences
    Given there are recurrences:
      | content | customer | company |
      | R1      | C1       | Acme    |
      | R2      | C1       | Acme    |
    When I visit "/recurrences"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 3 link with class ".create"

  Scenario: I view no recurrences I don't own
    Given there are recurrences:
      | content | customer | company |
      | R1      | C2       | Bros    |
      | R2      | C2       | Bros    |
    When I visit "/recurrences"
    Then I should see 0 "recurrence"
