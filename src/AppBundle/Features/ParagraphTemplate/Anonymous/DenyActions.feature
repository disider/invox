Feature: Anonymous cannot access paragraph template pages

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                         |
      | /paragraph-templates          |
      | /paragraph-templates/new      |
      | /paragraph-templates/0/edit   |
      | /paragraph-templates/0/delete |