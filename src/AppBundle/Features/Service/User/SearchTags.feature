Feature: User searches service tags
  In order to search for a service tags
  As a user
  I want to search for service tags

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
      | name | company | tags                 |
      | PR1  | Acme    | Category1, Category2 |
      | PR2  | Acme    | Category2            |
      | PR3  | Bros    | Category1            |

  Scenario: I can search for service tags
    Given I am logged as "user1@example.com"
    When I visit "/services/tags/search?term=Category"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "tags" property should contain 2 items

  Scenario: I cannot search if service module is disabled
    Given I am logged as "user2@example.com"
    When I try to visit "/services/tags/search?term=Category"
    Then the response status code should be 400
    And there should be response headers with:
      | Content-Type | application/problem+json |
    And the "tags" property should not exist

  Scenario: I can search for a service tag
    Given I am logged as "user1@example.com"
    When I visit "/services/tags/search?term=Category1"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "tags" property should contain 1 item
