Feature: Superadmin can edit a user
  In order to modify a user
  As an superadmin
  I want to edit user details

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | owner@acme.com         |            |
    And there is a company:
      | name | owner          |
      | Acme | owner@acme.com |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a user details
    When I visit "/users/%users.last.id%/edit"
    Then I should see the "user" fields:
      | email   | owner@acme.com |
      | enabled | 1              |
    And I should see the "user.ownedCompanies" options selected:
      | %companies.Acme.id% |

  Scenario: I can update a user
    When I visit "/users/%users.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a user
    When I visit "/users/%users.last.id%/edit"
    And I fill the "user.email" field with "newemail@acme.com"
    And I select the "user.ownedCompanies" options:
      | %companies.Acme.id% |
    And I press "Save and close"
    Then I should be on "/users"
    And I should see "newemail@acme.com"
