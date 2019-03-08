Feature: User can delete a service
  In order to delete a service
  As a user
  I want to delete a service

  Background:
    Given there is a user:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I delete a service
    Given there is a service:
      | name     | company |
      | Service1 | Acme    |
    When I visit "/services/%services.Service1.id%/delete"
    Then I should be on "/services"
    And I should see 0 "service"

  Scenario: I cannot delete a service I don't own
    Given there is a service:
      | name             | company |
      | NotOwnedService1 | Bros    |
    When I try to visit "/services/%services.NotOwnedService1.id%/delete"
    Then the response status code should be 403
