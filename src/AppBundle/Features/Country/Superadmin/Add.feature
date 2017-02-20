Feature: Superadmin can add a country
  In order to add a new country
  As a superadmin
  I want to add a country filling a form

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"
    When I visit "/countries/new"

  Scenario: I can add a country
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a country
    Given I fill the "country" fields with:
      | code                 | IT     |
      | translations.en.name | Italy  |
      | translations.it.name | Italia |
    When I press "Save and close"
    Then I should be on "/countries"
    And I should see 1 "country"

  Scenario: I cannot add a country without name
    When I press "Save and close"
    Then I should be on "/countries/new"
    And I should see a "Empty name" error
