Feature: Superadmin can delete a country
  In order to delete a country
  As a superadmin
  I want to delete a country

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a country:
      | code |
      | IT   |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a country
    When I visit "/countries/%countries.last.id%/delete"
    Then I should be on "/countries"
    And I should see 0 "country"
