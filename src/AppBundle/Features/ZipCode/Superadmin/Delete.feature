Feature: Superadmin can delete a zip code
  In order to delete a zip code
  As a superadmin
  I want to delete a zip code

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
    And there is a zip code:
      | code  | city |
      | 01234 | Rome |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a zip code
    When I visit "/zip-codes/%zipCodes.last.id%/delete"
    Then I should be on "/zip-codes"
    And I should see 0 "zip-code"
