Feature: User cannot access province pages

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
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                 |
      | /provinces                            |
      | /provinces/new                        |
      | /provinces/%provinces.last.id%/edit   |
      | /provinces/%provinces.last.id%/delete |
