Feature: Superadmin can delete a province
  In order to delete a province
  As a superadmin
  I want to delete a province

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
    And I am logged as "superadmin@example.com"

  Scenario: I delete a province
    When I visit "/provinces/%provinces.last.id%/delete"
    Then I should be on "/provinces"
    And I should see 0 "province"
