Feature: Superadmin can delete a city
  In order to delete a city
  As a superadmin
  I want to delete a city

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a country:
      | code |
      | IT   |
    And there is a province:
      | name | country |
      | Rome | IT      |
    And there is a city:
      | name | province |
      | Rome | Rome     |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a city
    When I visit "/cities/%cities.last.id%/delete"
    Then I should be on "/cities"
    And I should see 0 "city"
