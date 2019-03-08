Feature: Anonymous cannot access petty cash pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is an account:
      | name | company |
      | Bank | Acme    |
    And there is a petty cash note:
      | accountFrom | amount |
      | Bank        | 10     |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                             |
      | /petty-cash-notes                                 |
      | /petty-cash-notes/new                             |
      | /petty-cash-notes/%pettyCashNotes.last.id%/edit   |
      | /petty-cash-notes/%pettyCashNotes.last.id%/delete |
