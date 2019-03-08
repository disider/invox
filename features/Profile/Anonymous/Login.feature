Feature: Login

  Background:
    Given I am anonymous
    And I am on "/login"

  Scenario: I can login
    Given there is a user:
      | email            | password | role  |
      | user@example.com | secret   | owner |
    When I enter my credentials:
      | email            | password |
      | user@example.com | secret   |
    And I press "Login"
    Then I should be on "/dashboard"
    And I should see "Logout"
    And I should see "Welcome, user@example.com"

    And I should see the "/companies/new" link

  Scenario: I cannot login with empty email
    When I enter my credentials:
      | email | password  |
      |       | undefined |
    And I press "Login"
    Then I should see "Invalid credentials"

  Scenario: I cannot login with empty password
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    When I enter my credentials:
      | email            | password |
      | user@example.com |          |
    And I press "Login"
    Then I should see "Invalid credentials"

  Scenario: I cannot login with not registered email
    When I enter my credentials:
      | email                      | password   |
      | not-registered@example.com | irrelevant |
    And I press "Login"
    Then I should see "Invalid credentials"

  Scenario: I cannot login with not activated email
    Given there is an inactive user:
      | email                      | password   |
      | not-yet-active@example.com | irrelevant |
    When I enter my credentials:
      | email                      | password   |
      | not-yet-active@example.com | irrelevant |
    And I press "Login"

  Scenario: I cannot login with not activated email
    Given there is an inactive user:
      | email                      | password   |
      | not-yet-active@example.com | irrelevant |
    When I enter my credentials:
      | email                      | password   |
      | not-yet-active@example.com | irrelevant |
    And I press "Login"
    Then I should see "Your account is not yet confirmed"
    And I should see the "/register/not-yet-active@example.com/resend-confirmation" link

  Scenario: I cannot login with wrong password
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    When I enter my credentials:
      | email            | password      |
      | user@example.com | wrongpassword |
    And I press "Login"
    Then I should see "Invalid credentials"

  Scenario: I can't see the login page if I'm already logged
    Given there is a user:
      | email            |
      | user@example.com |
    And I am logged as "user@example.com"
    When I visit "/login"
    Then I am on "/"

  Scenario: I can reset my password
    When I visit "/login"
    Then I should see the "/reset-password" link
