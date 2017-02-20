Feature: Anonymous cannot access static page pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a page:
      | title | url  |
      | Page  | page |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                         |
      | /pages                        |
      | /pages/new                    |
      | /pages/%pages.last.id%/edit   |
      | /pages/%pages.last.id%/delete |
