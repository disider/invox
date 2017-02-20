Feature: User cannot access account pages

  Background:
    Given there is a user:
      | email             |
      | user@example.com  |
      | owner@example.com |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    And there is an account:
      | name | company |
      | Bank | Acme    |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                               |
      | /accounts                           |
      | /accounts/new                       |
      | /accounts/%accounts.Bank.id%/edit   |
      | /accounts/%accounts.Bank.id%/delete |
