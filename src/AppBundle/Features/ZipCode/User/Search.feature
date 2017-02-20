Feature: User searches for zip codes
  In order to search for zip codes
  As a user
  I want to search for zip codes within a city

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
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
    Given there are zip codes:
      | code  | city  |
      | 00001 | Rome  |
      | 00002 | Rome  |
      | 10003 | Milan |
    And I am logged as "user1@example.com"

  Scenario: I can search for a zip code
    When I visit "/zip-codes/search?term=rom"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "zipCodes" property should contain 2 items
