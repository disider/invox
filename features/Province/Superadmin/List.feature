Feature: Superadmin can list all provinces
  In order to view all provinces
  As a superadmin
  I want to view the list of all provinces

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a country:
      | code |
      | IT   |
    And I am logged as "superadmin@example.com"

  Scenario: I can add new provinces
    When I visit "/provinces"
    Then I should see the "/provinces/new" link

  Scenario: I view all provinces
    Given there is a province:
      | name | country |
      | Rome | IT      |
    When I visit "/provinces"
    Then I should see 1 "province"

  Scenario: I view the provinces paginated
    Given there are provinces:
      | name       | country |
      | Province 1 | IT      |
      | Province 2 | IT      |
      | Province 3 | IT      |
      | Province 4 | IT      |
      | Province 5 | IT      |
      | Province 6 | IT      |
    When I am on "/provinces"
    Then I should see 5 "province"
    When I am on "/provinces?page=2"
    Then I should see 1 "province"
    When I am on "/provinces?page=3"
    Then I should see 0 "province"

  Scenario: I can handle provinces
    Given there are provinces:
      | name       | country |
      | Province 1 | IT      |
      | Province 2 | IT      |
    When I visit "/provinces"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

