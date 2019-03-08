Feature: Superadmin can list all his accounts
  In order to view his accounts
  As a superadmin
  I want to view the list of all accounts

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I can add new accounts
    When I visit "/accounts"
    And I should see the "/accounts/new" link

  Scenario: I view all accounts
    Given there is an account:
      | name | company |
      | Bank | Acme    |
    When I visit "/accounts"
    Then I should see 1 "account"
    And I should see the "/accounts/new" link
