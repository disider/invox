Feature: Anonymous user cannot change any password
  As an anonymous user
  I cannot change any password

  Scenario: I can change another user's password
    When I visit "/profile/change-password"
    Then I should be on "/login"
