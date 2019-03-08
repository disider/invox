Feature: Superadmin can add an account
  In order to add a new account
  As an superadmin
  I want to add an account filling a form for any company

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
    When I visit "/accounts/new"

  Scenario: I can add an account
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add an account
    Given I fill the "account" fields with:
      | name | Bank |
    And I select the "account.company" option "Acme"
    When I press "Save and close"
    Then I should be on "/accounts"
    And I should see 1 "account"
