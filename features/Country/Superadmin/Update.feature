Feature: Superadmin can edit a country
  In order to modify a country
  As a superadmin
  I want to edit country details

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a country:
      | name  | code |
      | Italy | IT   |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a country details
    When I visit "/countries/%countries.last.id%/edit"
    Then I should see the "country" fields:
      | code                 | IT    |
      | translations.en.name | Italy |

  Scenario: I can update a country
    When I visit "/countries/%countries.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a country
    When I visit "/countries/%countries.last.id%/edit"
    And I fill the "country" fields with:
      | code                 | FR      |
      | translations.en.name | France  |
      | translations.it.name | Francia |
    And I press "Save and close"
    Then I should be on "/countries"
    And I should see "France"

  Scenario: I cannot edit an undefined country
    When I try to visit "/countries/0/edit"
    Then the response status code should be 404
