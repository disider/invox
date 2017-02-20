Feature: Anonymous cannot access module pages

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                            |
      | /modules                         |
      | /modules/petty-cash-notes/enable |
      | /modules/petty-cash/disable      |
