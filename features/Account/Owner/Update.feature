Feature: Owner can edit an account
  In order to modify an account
  As an owner
  I want to edit account details

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
    And I am logged as "user@example.com"

  Scenario: I can view an account details
    When I visit "/accounts/%accounts.Bank.id%/edit"
    Then I should see the "account" fields:
      | name | Bank |

  Scenario: I can update an account
    When I visit "/accounts/%accounts.Bank.id%/edit"
    Then I can press "Save"

  Scenario: I update an account
    When I visit "/accounts/%accounts.Bank.id%/edit"
    And I fill the "account" fields with:
      | name          | New name |
      | initialAmount | 100      |
      | currentAmount | 200      |
    And I press "Save and close"
    Then I should be on "/accounts"
    And I should see "New name"

  Scenario: I cannot edit an undefined account
    When I try to visit "/accounts/0/edit"
    Then the response status code should be 404
