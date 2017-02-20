Feature: User cannot access invite pages

  Background:
    Given there is a user:
      | email                  |
      | user@example.com       |
      | accountant@example.com |
      | owner@example.com      |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    And there is an accountant invite:
      | email                  | company |
      | accountant@example.com | Acme    |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                |
      | /invites                             |
      | /invites/%invites.last.token%/accept |
      | /invites/%invites.last.token%/refuse |
