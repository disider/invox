Feature: Owner can delete an account
  In order to delete an account
  As an owner
  I want to delete an account

  Background:
    Given there is a user:
      | email             | password |
      | user1@example.com | secret   |
      | user2@example.com | secret   |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I delete an account
    Given there is an account:
      | name | company |
      | Bank | Acme    |
    When I visit "/accounts/%accounts.Bank.id%/delete"
    Then I should be on "/accounts"
    And I should see 0 "account"

  Scenario: I cannot delete an account I don't own
    Given there is an account:
      | name         | company |
      | NotOwnedBank | Bros    |
    When I try to visit "/accounts/%accounts.NotOwnedBank.id%/delete"
    Then the response status code should be 403
