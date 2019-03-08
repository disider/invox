Feature: Superadmin can add a city
  In order to add a new city
  As a superadmin
  I want to add a city filling a form

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
    When I visit "/cities/new"

  Scenario: I can add a city
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a city
    Given I fill the "city" fields with:
      | name | Rome |
    And I select the "city.province" option "Rome"
    When I press "Save and close"
    Then I should be on "/cities"
    And I should see 1 "city"

  Scenario: I cannot add a city without name
    When I press "Save and close"
    Then I should be on "/cities/new"
    And I should see a "Empty name" error
