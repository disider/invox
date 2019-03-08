Feature: User cannot access zip code pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a country:
      | code |
      | IT   |
    And there is a province:
      | name | country |
      | Rome | IT      |
    And there is a city:
      | name | province |
      | Rome | Rome     |
    And there is a zip code:
      | code  | city |
      | 01234 | Rome |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                |
      | /zip-codes                           |
      | /zip-codes/new                       |
      | /zip-codes/%zipCodes.last.id%/edit   |
      | /zip-codes/%zipCodes.last.id%/delete |
