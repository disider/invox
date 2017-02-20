Feature: User cannot access account pages

  Background:
    Given there is a user:
      | email             |
      | user@example.com  |
      | owner@example.com |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    And there is a customer:
      | name | email                | company |
      | C1   | customer@example.com | Acme    |
    And there is a recurrence:
      | content | customer | company |
      | R1      | C1       | Acme    |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                     |
      | /recurrences                              |
      | /recurrences/new                          |
      | /recurrences/%recurrences.last.id%/edit   |
      | /recurrences/%recurrences.last.id%/delete |
