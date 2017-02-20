Feature: Superadmin can list all users
  In order to view all users
  As a superadmin
  I want to view the list of all users

  Scenario: I view all users
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user1@acme.com         | user       |
      | user2@bros.com         | user       |
    And there are companies:
      | name | owner          | manager        |
      | Acme | user1@acme.com | user2@bros.com |
      | Bros | user2@bros.com |                |
    And I am logged as "superadmin@example.com"
    When I visit "/users"
    Then I should see 3 "user"
    And I should see the "/users/new" link
