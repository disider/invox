Feature: Superadmin can add a province
  In order to add a new province
  As a superadmin
  I want to add a province filling a form

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a country:
      | code |
      | IT   |
    And I am logged as "superadmin@example.com"
    When I visit "/provinces/new"

  Scenario: I can add a province
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a province
    Given I fill the "province" fields with:
      | name | Rome |
      | code | RM   |
    And I select the "province.country" option "IT"
    When I press "Save and close"
    Then I should be on "/provinces"
    And I should see 1 "province"

  Scenario: I cannot add a province without name
    When I press "Save and close"
    Then I should be on "/provinces/new"
    And I should see a "Empty name" error
