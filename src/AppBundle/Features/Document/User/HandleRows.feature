Feature: User handles document rows
  In order to modify a document's rows
  As a user
  I want to add, delete, edit and move document rows

  Background:
    Given there are users:
      | email             | password |
      | user1@example.com | secret   |
      | user2@example.com | secret   |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there are quotes:
      | user              | customer              | ref | year | company |
      | user1@example.com | customer1@example.com | D01 | 2014 | Acme    |
      | user2@example.com | customer2@example.com | D02 | 2014 | Bros    |
    And there are tax rates:
      | name | amount |
      | 10%  | 10     |
      | 20%  | 20     |
    And there are document rows:
      | document | title     | unitPrice | quantity | taxRate |
      | D01      | Product 1 | 100       | 1        | 10      |
      | D01      | Product 2 | 200       | 2        | 20      |
    And I am logged as "user1@example.com"
    And I visit "/documents/%documents.D01.id%/edit"

  @javascript
  Scenario: I can add a row
    When I click on "Add row"
    Then I should see 3 "document-row"s

  @javascript
  Scenario: I can delete a row
    When I click on "Delete row"
    Then I should see 1 "document-row"

  @javascript
  Scenario: I can add a row after deleting a row
    When I click on "Delete row"
    And I click on "Add row"
    Then I should see 2 "document-row"s

# TODO: Restore this test
#  @javascript
#  Scenario: I can move a row up
#    Given I visit "/documents/%documents.D01.id%/edit"
#    When I click on "Move up" within ".document-row.1"
#    Then I should see the "document.rows.0" fields:
#      | title | Product 2 |
#    And I should see the "document.rows.1" fields:
#      | title | Product 1 |

  @javascript
  Scenario: I can move a row down
    Given I visit "/documents/%documents.D01.id%/edit"
    When I click on "Move down"
    Then I should see the "document.rows.0" fields:
      | title | Product 2 |
    And I should see the "document.rows.1" fields:
      | title | Product 1 |
