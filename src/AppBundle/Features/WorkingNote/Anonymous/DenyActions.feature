Feature: Anonymous cannot access working note pages

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                   |
      | /working-notes          |
      | /working-notes/new      |
      | /working-notes/0/edit   |
      | /working-notes/0/delete |