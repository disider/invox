Feature: Anonymous cannot access user pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |

  Scenario Outline: Denied actions
    When I visit "<route>"
    Then I should be on "/login"
    Examples:
      | route                         |
      | /users                        |
      | /users/new                    |
      | /users/%users.last.id%/edit   |
      | /users/%users.last.id%/delete |
