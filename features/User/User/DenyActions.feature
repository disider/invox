Feature: User cannot access user pages

  Background:
    Given there is a user:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario Outline: Denied actions
    When I try to visit "<route>"
    Then the response status code should be 403
    Examples:
      | route                         |
      | /users                        |
      | /users/new                    |
      | /users/%users.last.id%/edit   |
      | /users/%users.last.id%/delete |
