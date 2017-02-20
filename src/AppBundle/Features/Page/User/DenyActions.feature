Feature: User cannot access page pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a page:
      | title |
      | Page  |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                         |
      | /pages                        |
      | /pages/new                    |
      | /pages/%pages.last.id%/edit   |
      | /pages/%pages.last.id%/delete |
