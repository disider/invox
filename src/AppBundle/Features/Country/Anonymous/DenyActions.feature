Feature: Anonymous cannot access country pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a country:
      | code |
      | IT   |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                 |
      | /countries                            |
      | /countries/new                        |
      | /countries/%countries.last.id%/edit   |
      | /countries/%countries.last.id%/delete |
