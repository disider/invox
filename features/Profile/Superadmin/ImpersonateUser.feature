Feature: Superadmin can impersonate a user
  In order to act as a user
  As an superadmin
  I want to impersonate another user

  Background:
    Given there are users:
      | email                  | password | role       |
      | superadmin@example.com | secret   | superadmin |
      | owner@example.com      | secret   | owner      |
    And I am logged as "superadmin@example.com"

  Scenario: I can impersonate another user
    When I visit "/users"
    Then I should see the "/dashboard?_switch_user=owner@example.com" link
    And I should see no "/dashboard?_switch_user=superadmin@example.com" link
    And I should see no "/dashboard?_switch_user=_exit" link

  Scenario: I impersonate another user
    When I visit "/dashboard?_switch_user=owner@example.com"
    Then I should be on "/dashboard"
    And I should see "owner@example.com"
    And I should see the "/dashboard?_switch_user=_exit" link
