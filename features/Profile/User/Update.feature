Feature: User can edit his profile
  In order to modify my profile
  As a user
  I want to edit my user details

  Background:
    Given there are users:
      | username | email            |
      | user     | user@example.com |
    And I am logged as "user@example.com"

  Scenario: I can update my details
    When I visit "/profile"
    Then I can press "Save"
    And I can press "Save and close"
    And I should see the "/profile/change-password" link
    But I should not see the "profile.email" field

  Scenario: I update my details
    When I visit "/profile"
    And there should be no "user[email]" field
    And there should be no "user[roles][]" field
    And I press "Save and close"
    Then I should be on "/dashboard"
    And I should see "Profile successfully updated"
