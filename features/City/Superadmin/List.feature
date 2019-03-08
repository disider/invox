Feature: Superadmin can list all cities
  In order to view all cities
  As a superadmin
  I want to view the list of all cities

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

  Scenario: I can add new cities
    When I visit "/cities"
    Then I should see the "/cities/new" link

  Scenario: I view all cities
    Given there is a city:
      | name | province |
      | Rome | Rome     |
    When I visit "/cities"
    Then I should see 1 "city"

  Scenario: I view the cities paginated
    Given there are cities:
      | name   | province |
      | City 1 | Rome     |
      | City 2 | Rome     |
      | City 3 | Rome     |
      | City 4 | Rome     |
      | City 5 | Rome     |
      | City 6 | Rome     |
    When I am on "/cities"
    Then I should see 5 "city"
    When I am on "/cities?page=2"
    Then I should see 1 "city"
    When I am on "/cities?page=3"
    Then I should see 0 "city"

  Scenario: I can handle cities
    Given there are cities:
      | name   | province |
      | City 1 | Rome     |
      | City 2 | Rome     |
    When I visit "/cities"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

