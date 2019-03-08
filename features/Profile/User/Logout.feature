Feature: Logout

  Scenario: I cannot logout if anonymous
    When I visit "/logout"
    Then I should be on "/login"

  Scenario: I can logout if I'm logged in
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And I am logged as "user@example.com"
    And I visit "/"
    When I click the "Logout" link
    Then I should see "Login"
    And I should be on "/login"
