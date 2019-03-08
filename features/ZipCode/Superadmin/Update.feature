Feature: Superadmin can edit a zip code
  In order to modify a zip code
  As a superadmin
  I want to edit zip code details

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
    And there are cities:
      | name  | province |
      | Rome  | Rome     |
      | Milan | Milan    |
    And there is a zip code:
      | code  | city |
      | 01234 | Rome |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a zip code details
    When I visit "/zip-codes/%zipCodes.last.id%/edit"
    Then I should see the "zipCode" fields:
      | code | 01234 |
    And I should see the "zipCode.city" option "Rome" selected

  Scenario: I can update a zip code
    When I visit "/zip-codes/%zipCodes.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a zip code
    When I visit "/zip-codes/%zipCodes.last.id%/edit"
    And I fill the "zipCode" fields with:
      | code | 54321 |
    And I select the "zipCode.city" option "Milan"
    And I press "Save and close"
    Then I should be on "/zip-codes"
    And I should see "54321"

  Scenario: I cannot edit an undefined zip code
    When I try to visit "/zip-codes/0/edit"
    Then the response status code should be 404
