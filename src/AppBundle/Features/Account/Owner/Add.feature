Feature: Owner can add an account
  In order to add a new account
  As an owner
  I want to add an account filling a form

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "user@example.com"
    When I visit "/accounts/new"

  Scenario: I can add an account
    Then I should not see the "account" fields:
      | currentAmount |
    And I can press "Save"
    And I can press "Save and close"

  Scenario: I add an account
    Given I fill the "account" fields with:
      | name          | Bank |
      | initialAmount | 100  |
    And I select the "account.type" option "Bank account"
    When I press "Save and close"
    Then I should be on "/accounts"
    And I should see 1 "account"
    And I should see the "account" rows:
      | type         | name | initial-amount | current-amount |
      | Bank account | Bank | 100            | 100            |

  Scenario: I cannot add an account without name
    When I press "Save and close"
    Then I should be on "/accounts/new"
    And I should see a "Empty name" error
