Feature: User searches document cost centers
  In order to search for a document cost centers
  As a user
  I want to search for document cost centers

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there are quotes:
      | ref | customer              | company | costCenters |
      | 001 | customer1@example.com | Acme    | Center1     |
      | 002 | customer1@example.com | Acme    | Center2     |
      | 003 | customer1@example.com | Acme    | Center2     |
      | 004 | customer2@example.com | Bros    | Center1     |

  Scenario: I can search for document cost centers
    Given I am logged as "user1@example.com"
    When I visit "/documents/cost-centers/search?term=Center"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "costCenters" property should contain 2 items

  Scenario: I can search for a document cost center
    Given I am logged as "user1@example.com"
    When I visit "/documents/cost-centers/search?term=Center1"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "costCenters" property should contain 1 item
