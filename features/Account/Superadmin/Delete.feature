Feature: Superadmin can delete any account
  In order to delete an account
  As a superadmin
  I want to delete an account

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is an account:
      | name | company |
      | Bank | Acme    |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I can delete any account
    When I visit "/accounts/%accounts.Bank.id%/delete"
    Then I should be on "/accounts"
    And I should see 0 "account"
