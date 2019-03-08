Feature: User changes his password
  In order to modify my password
  As a user
  I want to edit my password

  Background:
    Given there are users:
      | username | email             | password | role |
      | user1    | user1@example.com | secret   | user |
      | user2    | user2@example.com | secret   | user |
    And I am logged as "user1@example.com"

  Scenario: I can change my password
    When I visit "/profile/change-password"
    Then I can press "Save"

  Scenario: I change my password
    When I visit "/profile/change-password"
    And I fill the "changePassword" form with:
      | currentPassword | newPassword |
      | secret          | newsecret   |
    And I press "Save"
    Then I should be on "/profile"
    And I should see "Password successfully updated"

  Scenario: I cannot change my password when giving an empty new password
    When I visit "/profile/change-password"
    And I fill the "changePassword" form with:
      | currentPassword | newPassword |
      | secret          |             |
    And I press "Save"
    Then I should be on "/profile/change-password"
    And I should see a "Empty password" error

  Scenario: I cannot change my password when giving a wrong current
    When I visit "/profile/change-password"
    And I fill the "changePassword" form with:
      | currentPassword | newPassword |
      | wrongsecret     | newsecret   |
    And I press "Save"
    Then I should be on "/profile/change-password"
    And I should see a "Please enter your current password" error

  Scenario: I cannot change my password when demo mode is enabled
    Given the demo mode is enabled
    When I visit "/profile/change-password"
    Then I should be on "/dashboard"
    And I should see "This action is not allowed in the demo"
