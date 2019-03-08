Feature: Anonymous cannot access of the recurrence pages

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
    And there is a recurrence:
      | content | customer | company |
      | R1      | C1       | Acme    |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                     |
      | /recurrences                              |
      | /recurrences/new                          |
      | /recurrences/%recurrences.last.id%/edit   |
      | /recurrences/%recurrences.last.id%/delete |
