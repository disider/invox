Feature: User can register
  In order to access the platform
  As a user
  I want to register

  Scenario: I request a reset password
    Given there is a user:
      | email            |
      | user@example.com |
    And I am on "/reset-password"
    When I fill the "requestResetPassword" form with:
      | email            |
      | user@example.com |
    And I press "Request"
    Then I should be on "/reset-password/request-sent"
    And a "request_reset_password" email should be sent to "user@example.com"

  Scenario: I reset my password
    Given there is a user:
      | email            | password | resetPasswordToken |
      | user@example.com | secret   | 12345678           |
    When I visit "/reset-password/reset/12345678"
    And I fill the "resetPassword" form with:
      | password  |
      | newsecret |
    And I press "Reset"
    Then I should see the "/logout" link
    And I should see "Your password has been reset"

  Scenario: I can request a reset password even if I'm not registered
    Given I am on "/reset-password"
    When I fill the "requestResetPassword" form with:
      | email               |
      | unknown@example.com |
    And I press "Request"
    Then I should be on "/reset-password/request-sent"
    But no "request_reset_password" email should be sent to "unknown@example.com"

  Scenario: I can request a reset password even if I'm not activated
    Given there is an inactive user:
      | email                      | password   |
      | not-yet-active@example.com | irrelevant |
    Given I am on "/reset-password"
    When I fill the "requestResetPassword" form with:
      | email                      |
      | not-yet-active@example.com |
    And I press "Request"
    Then I should see "Your account is not yet confirmed"
    And I should see the "/register/not-yet-active@example.com/resend-confirmation" link

  Scenario: I cannot request a reset password with an invalid email
    Given there is a user:
      | email            |
      | user@example.com |
    And I am on "/reset-password"
    When I fill the "requestResetPassword" form with:
      | email |
      | wrong |
    And I press "Request"
    Then I should see an "Invalid email" error

  Scenario: I cannot reset my password with an unknown token
    When I try to visit "/reset-password/reset/12345678"
    Then the response status code should be 404

  Scenario: I cannot reset my password if I'm logged in
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And I am logged as "user@example.com"
    When I visit "/reset-password"
    Then I should be on "/dashboard"

  Scenario: I cannot reset my password twice
    Given there is a user:
      | email            | password | resetPasswordToken |
      | user@example.com | secret   | 12345678           |
    And I visit "/reset-password/reset/12345678"
    And I fill the "resetPassword" form with:
      | password  |
      | newsecret |
    And I press "Reset"
    When I visit "/logout"
    And I try to visit "/reset-password/reset/12345678"
    Then the response status code should be 404
