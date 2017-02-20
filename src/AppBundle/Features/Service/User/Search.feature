Feature: User searches a service
  In order to search for a service
  As a user
  I want to search for services

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             | modules  |
      | Acme | user1@example.com | services |
      | Bros | user2@example.com |          |
    Given there are services:
      | name | company |
      | SR1  | Acme    |
      | SR2  | Acme    |
      | SR3  | Bros    |

  Scenario: I can search for a service
    Given I am logged as "user1@example.com"
    When I visit "/services/search?term=SR"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "services" property should contain 2 items

  Scenario: I cannot search if service module is disabled
    Given I am logged as "user2@example.com"
    When I try to visit "/services/search?term=PR"
    Then the response status code should be 400
    And there should be response headers with:
      | Content-Type | application/problem+json |
    And the "services" property should not exist

  Scenario: I can search for a service
    Given I am logged as "user1@example.com"
    When I visit "/services/search?term=SR1"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "services" property should contain 1 item
