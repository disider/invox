Feature: Anonymous cannot access account pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is an account:
      | name | company |
      | Bank | Acme    |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                               |
      | /accounts                           |
      | /accounts/new                       |
      | /accounts/%accounts.Bank.id%/edit   |
      | /accounts/%accounts.Bank.id%/delete |
