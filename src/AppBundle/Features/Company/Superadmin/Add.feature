Feature: Superadmin can add a company
  In order to add a company
  As a superadmin
  I want to add a company filling a form

  Background:
    Given there is a user:
      | email                  | password | role       |
      | superadmin@example.com | secret   | superadmin |
      | user@example.com       | secret   | user       |
    And I am logged as "superadmin@example.com"
    When I visit "/companies/new"

  Scenario: I can add a company
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a company
    Given I fill the "company" fields with:
      | name      | Acme        |
      | vatNumber | 01234567890 |
    And I select the "company.owner" option "user@example.com"
    When I press "Save and close"
    Then I should be on "/companies"
    And I should see 1 "company"

  Scenario: I cannot add a company without name
    When I press "Save and close"
    Then I should be on "/companies/new"
    And I should see a "Empty name" error
