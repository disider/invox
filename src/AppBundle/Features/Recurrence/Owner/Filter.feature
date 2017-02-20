Feature: Owner filters his recurrences
  In order to view my recurrences details
  As a user
  I want to filter my recurrences

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there are customers:
      | name | email                 | company |
      | C1   | customer@example.com  | Acme    |
      | C2   | customer2@example.com | Acme    |
    And there are recurrences:
      | direction | content | customer | company | startAt | repeats     |
      | ingoing   | R1      | C1       | Acme    | now     | every_month |
      | outgoing  | R2      | C2       | Acme    | now     | every_day   |
    And I am logged as "user@example.com"
    And I visit "/recurrences"

  Scenario: I can filter the recurrences
    Then I can press "Filter"

  Scenario: I filter the recurrence by customer
    Given I fill the "recurrenceFilter[customer]" field with "C1"
    When I press "Filter"
    And I should see 1 "recurrence"

  Scenario: I filter the recurrence by direction
    Given I fill the "recurrenceFilter[direction]" field with "outgoing"
    When I press "Filter"
    Then I should see 1 "outgoing"s

  Scenario Outline: I filter the recurrence by start at (from)
    Given I fill the "recurrenceFilter[startAt][left_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <recurrence> "recurrence"s

    Examples:
      | date        | recurrence |
      | now         | 2          |
      | now +2 days | 0          |
      | now -2 days | 2          |

  Scenario Outline: I filter the recurrence by start at (to)
    Given I fill the "recurrenceFilter[startAt][right_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <recurrence> "recurrence"s

    Examples:
      | date        | recurrence |
      | now         | 2          |
      | now +2 days | 2          |
      | now -2 days | 0          |

  Scenario Outline: I filter the recurrence by next due date (from)
    Given I fill the "recurrenceFilter[nextDueDate][left_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <recurrence> "recurrence"s

    Examples:
      | date        | recurrence |
      | now         | 2          |
      | now +2 days | 0          |
      | now -2 days | 2          |

  Scenario Outline: I filter the recurrence by next due date (to)
    Given I fill the "recurrenceFilter[nextDueDate][right_date]" field with "%date('d/m/Y', '<date>')%"
    When I press "Filter"
    Then I should see <recurrence> "recurrence"s

    Examples:
      | date        | recurrence |
      | now         | 2          |
      | now +2 days | 2          |
      | now -2 days | 0          |
