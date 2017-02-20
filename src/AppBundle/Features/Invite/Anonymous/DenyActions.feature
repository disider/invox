Feature: Anonymous cannot access invite pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is an accountant invite:
      | email                  | company |
      | accountant@example.com | Acme    |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                |
      | /invites/%invites.last.token%/accept |
      | /invites/%invites.last.token%/refuse |
