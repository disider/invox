Feature: Anonymous cannot access city pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a country:
      | code |
      | IT   |
    And there is a province:
      | name | country |
      | Rome | IT      |
    And there is a city:
      | name | province |
      | Rome | Rome     |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                           |
      | /cities                         |
      | /cities/new                     |
      | /cities/%cities.last.id%/edit   |
      | /cities/%cities.last.id%/delete |
