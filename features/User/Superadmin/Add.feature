Feature: Superadmin can add a user
  In order to add a user
  As a superadmin
  I want to add a user filling a form

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | owner@example.com      | owner      |
    And there are companies:
      | name | owner             |
      | Acme | owner@example.com |
      | Bros | owner@example.com |
    And I am logged as "superadmin@example.com"
    When I visit "/users/new"

  Scenario: I add a superadmin user
    Given I fill the "user.email" field with "user@example.com"
    And I fill the "user.password" field with "usersecret"
    And I check the "user.enabled" field
    And I check the "user.isSuperAdmin" field
    When I press "Save and close"
    Then I should be on "/users"
    And I should see 3 "user"
    And I should see 2 "superadmin"

  Scenario: I add a user
    Given I fill the "user.email" field with "user@example.com"
    And I fill the "user.password" field with "usersecret"
    And I check the "user.enabled" field
    And I select the "user.ownedCompanies" options:
      | %companies.Acme.id% |
    And I select the "user.managedCompanies" options:
      | %companies.Bros.id% |
    When I press "Save and close"
    Then I should be on "/users"
    And I should see 3 "user"
    And I should see 1 "superadmin"

  Scenario: I add a sales agent
    Given I fill the "user.email" field with "user@example.com"
    And I fill the "user.password" field with "usersecret"
    And I check the "user.enabled" field
    And I select the "user.marketedCompanies" options:
      | %companies.Acme.id% |
    When I press "Save and close"
    Then I should be on "/users"
    And I should see 3 "user"
    And I should see 1 "superadmin"
