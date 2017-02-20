Feature: Owner cannot access account pages if the account module is disabled

  Background:
    Given there are users:
      | email             |
      | owner@example.com |
    And there is a company:
      | name | owner             | modules |
      | Acme | owner@example.com |         |
    And there is an account:
      | name  | company |
      | Bank1 | Acme    |
    And I am logged as "owner@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                               |
      | /accounts                           |
      | /accounts/new                       |
      | /accounts/%accounts.last.id%/edit   |
      | /accounts/%accounts.last.id%/delete |
