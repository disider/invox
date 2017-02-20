Feature: Anonymous cannot access province pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a country:
      | code |
      | IT   |
    And there is a province:
      | name | code | country |
      | Rome | RM   | IT      |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                 |
      | /provinces                            |
      | /provinces/new                        |
      | /provinces/%provinces.last.id%/edit   |
      | /provinces/%provinces.last.id%/delete |
