Feature: Superadmin can delete a company
  In order to delete a company
  As a superadmin
  I want to delete a company

  Background:
    Given there is a user:
      | email                  | password | role       |
      | superadmin@example.com | secret   | superadmin |
      | user@example.com       | secret   | user       |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a company
    Given there is a company:
      | name | owner            |
      | Acme | user@example.com |
    When I visit "/companies/%companies.Acme.id%/delete"
    Then I should be on "/companies"
    And I should see 0 "company"

  Scenario: I cannot delete a company in demo mode
    Given there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And the demo mode is enabled
    When I visit "/companies/%companies.Acme.id%/delete"
    Then I should be on "/companies"
    And I should see "This action is not allowed in the demo"