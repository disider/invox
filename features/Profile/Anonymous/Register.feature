Feature: User can register
  In order to access the platform
  As a user
  I want to register

  Scenario: I register
    Given I am on "/register"
    When I fill the "registration" form with:
      | email            | password |
      | user@example.com | secret   |
    And I press "Register"
    Then I should be on "/register/request-confirmation"
    And a "registration_confirm" email should be sent to "user@example.com"
    And the "%users.last.roles%" entity property should contain "ROLE_OWNER"

  @email
  Scenario: I confirm my registration
    Given there is a user:
      | email            | confirmationToken | role  |
      | user@example.com | 12345678          | owner |
    When I visit "/register/confirm/12345678"
    Then a "registration_completed" email should be sent to "user@example.com"
    Then I should be on "/dashboard"
    And I should see the "/logout" link
    And I should see "Your account is confirmed"
    And I should see the "/companies/new" link

  Scenario: I cannot register if I'm logged in
    Given there is a user:
      | email            |
      | user@example.com |
    And  I am logged as "user@example.com"
    When I visit "/register"
    Then I should be on "/dashboard"

  Scenario: I cannot register If I'm already registered
    Given there is a user:
      | email            |
      | user@example.com |
    When I visit "/register"
    And I fill the "registration" form with:
      | email            |
      | user@example.com |
    And I press "Register"
    Then I should see an "Email already registered" error

  Scenario: I cannot request a registration confirmation if I'm logged in
    Given there is a user:
      | email            |
      | user@example.com |
    And  I am logged as "user@example.com"
    When I visit "/register/request-confirmation"
    Then I should be on "/dashboard"

  Scenario: I cannot confirm my registration if I'm logged in
    Given there is a user:
      | email            |
      | user@example.com |
    And  I am logged as "user@example.com"
    When I visit "/register/confirm/123"
    Then I should be on "/dashboard"

  @email
  Scenario: I ask to resend the confirmation email
    Given there is a user:
      | email            | password | confirmationToken |
      | user@example.com | secret   | 12345678          |
    When I am on "/register/user@example.com/resend-confirmation"
    Then a "registration_confirm" email should be sent to "user@example.com"

  Scenario: I cannot ask to resend the confirmation email for an unknown email
    When I am on "/register/unknown/resend-confirmation"
    Then the response status code should be 404

  Scenario: I cannot ask to resend the confirmation email if I'm already confirmed
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    When I am on "/register/user@example.com/resend-confirmation"
    Then the response status code should be 400
