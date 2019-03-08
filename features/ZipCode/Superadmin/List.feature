Feature: Superadmin can list all zipCodes
  In order to view all zipCodes
  As a superadmin
  I want to view the list of all zipCodes

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a country:
      | code |
      | IT   |
    And there is a province:
      | name | country |
      | Rome | It      |
    And there is a city:
      | name | province |
      | Rome | Rome     |
    And I am logged as "superadmin@example.com"

  Scenario: I can add new zipCodes
    When I visit "/zip-codes"
    Then I should see the "/zip-codes/new" link

  Scenario: I view all zipCodes
    Given there is a zip code:
      | code  | city |
      | 01234 | Rome |
    When I visit "/zip-codes"
    Then I should see 1 "zip-code"

  Scenario: I view the zipCodes paginated
    Given there are zip codes:
      | code  | city |
      | 00001 | Rome |
      | 00002 | Rome |
      | 00003 | Rome |
      | 00004 | Rome |
      | 00005 | Rome |
      | 00006 | Rome |
    When I am on "/zip-codes"
    Then I should see 5 "zip-code"
    When I am on "/zip-codes?page=2"
    Then I should see 1 "zip-code"
    When I am on "/zip-codes?page=3"
    Then I should see 0 "zip-code"

  Scenario: I can handle zipCodes
    Given there are zip codes:
      | code  | city |
      | 00001 | Rome |
      | 00002 | Rome |
    When I visit "/zip-codes"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

