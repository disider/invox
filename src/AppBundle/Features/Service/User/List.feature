Feature: User can list all his services
  In order to view his services
  As a user
  I want to view the list of all my services

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I can add new services
    When I visit "/services"
    Then I should see the "/services/new" link

  Scenario: I view all my services
    Given there is a service:
      | name     | company |
      | Service1 | Acme    |
    When I visit "/services"
    Then I should see 1 "service"

  Scenario: I view the services paginated
    Given there are services:
      | name      | company |
      | Service 1 | Acme    |
      | Service 2 | Acme    |
      | Service 3 | Acme    |
      | Service 4 | Acme    |
      | Service 5 | Acme    |
      | Service 6 | Acme    |
    When I am on "/services"
    Then I should see 5 "service"
    When I am on "/services?page=2"
    Then I should see 1 "service"
    When I am on "/services?page=3"
    Then I should see 0 "service"

  Scenario: I can handle services
    Given there are services:
      | name     | company |
      | Service1 | Acme    |
      | Service2 | Acme    |
    When I visit "/services"
    Then I should see 4 link with class ".edit"
    And I should see 2 link with class ".delete"
    And I should see 1 link with class ".create"

  Scenario: I view no services I don't own
    Given there are services:
      | name      | company |
      | Service 1 | Bros    |
      | Service 2 | Bros    |
    When I visit "/services"
    Then I should see 0 "service"

  Scenario: I can filter service by code
    Given there are services:
      | name            | code | company |
      | Service 1       | 001  | Acme    |
      | Other Service 2 | 002  | Acme    |
    When I visit "/services"
    When I fill the "serviceFilter.code" field with "001"
    And I press "Filter"
    Then I should be on "/services"
    And I should see 1 "service"
    And I should see "Service 1"

  Scenario: I can filter service by name
    Given there are services:
      | name            | code | company |
      | Service 1       | 001  | Acme    |
      | Other Service 2 | 002  | Acme    |
    When I visit "/services"
    When I fill the "serviceFilter.name" field with "Other Service"
    And I press "Filter"
    Then I should be on "/services"
    And I should see 1 "service"
    And I should see "Other Service 2"
