Feature: Superadmin can add a zip code
  In order to add a new zip code
  As a superadmin
  I want to add a zip code filling a form

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a country:
      | code |
      | It   |
    And there is a province:
      | name | country |
      | Rome | IT      |
    And there is a city:
      | name | province |
      | Rome | Rome     |
    And I am logged as "superadmin@example.com"
    When I visit "/zip-codes/new"

  Scenario: I can add a zip code
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a zip code
    Given I fill the "zipCode" fields with:
      | code | 01234 |
    And I select the "zipCode.city" option "Rome"
    When I press "Save and close"
    Then I should be on "/zip-codes"
    And I should see 1 "zip-code"

  Scenario: I cannot add a zip code without code
    When I press "Save and close"
    Then I should be on "/zip-codes/new"
    And I should see a "Empty code" error
