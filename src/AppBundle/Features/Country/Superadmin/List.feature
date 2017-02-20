Feature: Superadmin can list all countries
  In order to view all countries
  As a superadmin
  I want to view the list of all countries

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"

  Scenario: I can add new countries
    When I visit "/countries"
    Then I should see the "/countries/new" link

  Scenario: I view all countries
    Given there is a country:
      | code |
      | IT   |
    When I visit "/countries"
    Then I should see 1 "country"

  Scenario: I view the countries paginated
    Given there are countries:
      | code |
      | C1   |
      | C2   |
      | C3   |
      | C4   |
      | C5   |
      | C6   |
    When I am on "/countries"
    Then I should see 5 "country"
    When I am on "/countries?page=2"
    Then I should see 1 "country"
    When I am on "/countries?page=3"
    Then I should see 0 "country"

  Scenario: I can handle countries
    Given there are countries:
      | code |
      | C1   |
      | C2   |
    When I visit "/countries"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

