Feature: Superadmin can delete a user
  In order to get rid of a user
  As an superadmin
  I want to delete a user

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | owner@acme.com         |            |
    And there is a company:
      | name | owner          |
      | Acme | owner@acme.com |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a user
    When I visit "/users/%users.last.id%/delete"
    Then I should be on "/users"
    And I should see "successfully deleted"
    And I should see 1 "user"
