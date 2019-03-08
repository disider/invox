Feature: User cannot access city pages

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
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                           |
      | /cities                         |
      | /cities/new                     |
      | /cities/%cities.last.id%/edit   |
      | /cities/%cities.last.id%/delete |
