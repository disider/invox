Feature: Superadmin can edit a city
  In order to modify a city
  As a superadmin
  I want to edit city details

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a country:
      | code |
      | IT   |
    And there are provinces:
      | name  | country |
      | Rome  | IT      |
      | Milan | IT      |
    And there is a city:
      | name | province |
      | Rome | Rome     |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a city details
    When I visit "/cities/%cities.last.id%/edit"
    Then I should see the "city" fields:
      | name | Rome |
    And I should see the "city.province" option "Rome" selected

  Scenario: I can update a city
    When I visit "/cities/%cities.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a city
    When I visit "/cities/%cities.last.id%/edit"
    And I fill the "city" fields with:
      | name | Milan |
    And I select the "city.province" option "Milan"
    And I press "Save and close"
    Then I should be on "/cities"
    And I should see "Milan"

  Scenario: I cannot edit an undefined city
    When I try to visit "/cities/0/edit"
    Then the response status code should be 404
