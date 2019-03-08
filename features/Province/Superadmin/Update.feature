Feature: Superadmin can edit a province
  In order to modify a province
  As a superadmin
  I want to edit province details

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there are countries:
      | code | name           |
      | IT   | Italy          |
      | UK   | United Kingdom |
    And there is a province:
      | name | code | country |
      | Rome | RM   | IT      |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a province details
    When I visit "/provinces/%provinces.last.id%/edit"
    Then I should see the "province" fields:
      | name | Rome |
      | code | RM   |
    And I should see the "province.country" option "Italy" selected

  Scenario: I can update a province
    When I visit "/provinces/%provinces.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a province
    When I visit "/provinces/%provinces.last.id%/edit"
    And I fill the "province" fields with:
      | name | London |
      | code | LND    |
    And I select the "province.country" option "United Kingdom"
    And I press "Save and close"
    Then I should be on "/provinces"
    And I should see "London"

  Scenario: I cannot edit an undefined province
    When I try to visit "/provinces/0/edit"
    Then the response status code should be 404
