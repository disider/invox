Feature: Superadmin can edit a company
  In order to modify a company
  As a superadmin
  I want to edit company details

  Background:
    Given there is a user:
      | email                  | password | role       |
      | superadmin@example.com | secret   | superadmin |
      | user@example.com       | secret   | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a company details
    When I visit "/companies/%companies.Acme.id%/edit"
    Then I should see the "company" fields:
      | name | Acme |

  Scenario: I can update a company
    When I visit "/companies/%companies.Acme.id%/edit"
    Then I can press "Save"

  Scenario: I update a company
    When I visit "/companies/%companies.Acme.id%/edit"
    Given I fill the "company" fields with:
      | name      | Bros        |
      | vatNumber | 01234567890 |
    And I press "Save and close"
    Then I should be on "/companies"
    And I should see "Bros"
