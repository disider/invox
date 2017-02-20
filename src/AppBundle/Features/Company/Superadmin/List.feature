Feature: Superadmin can list all companies
  In order to view all companies
  As a superadmin
  I want to view the list of all companies

  Background:
    Given there is a user:
      | email                  | password | role       |
      | superadmin@example.com | secret   | superadmin |
      | user@example.com       | secret   | user       |
    And I am logged as "superadmin@example.com"

  Scenario: I view all companies
    Given there is a company:
      | name | owner            |
      | Acme | user@example.com |
    When I visit "/companies"
    Then I should see 1 "company"
