Feature: Anonymous cannot access medium pages

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route           |
      | /media          |
      | /media/new      |
      | /media/0/edit   |
      | /media/0/delete |