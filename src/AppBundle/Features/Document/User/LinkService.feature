Feature: User links an order
  In order to link orders and invoices
  As a user
  I want to select the linked order

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there is a customer:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Acme    |
    And there are tax rates:
      | name | amount |
      | 0%   | 0      |
      | 10%  | 10     |
    And there are services:
      | name | company | unitPrice | taxRate |
      | PR1  | Acme    | 100       | 10      |
      | PR2  | Acme    | 200       | 0       |
    And there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    And I am logged as "user1@example.com"

  Scenario: I see a linked service
    Given there are document rows:
      | document | title     | unitPrice | quantity | service |
      | I01      | Service 1 | 100       | 1        | PR1     |
      | I01      | Service 2 | 200       | 2        | PR2     |
    When I visit "/documents/%documents.I01.id%/edit"
    Then I should see the "document.rows.0.linkedService" field with "%services.PR1.id%"

  @javascript
  Scenario: I unlink a linked service
    Given there are document rows:
      | document | title     | unitPrice | quantity | service |
      | I01      | Service 1 | 100       | 1        | PR1     |
      | I01      | Service 2 | 200       | 2        | PR2     |
    When I visit "/documents/%documents.I01.id%/edit"
    And I click on "Service:"
    Then I should see the "document.rows.0.linkedService" field with ""

  Scenario: I link a service
    Given there are document rows:
      | document | title   | unitPrice | quantity |
      | I01      | Title 1 | 100       | 1        |
      | I01      | Title 2 | 200       | 2        |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.rows.0.linkedService" field with "%services.PR1.id%"
    And I press "Save"
    Then I should see the "document.rows.0.linkedService" field with "%services.PR1.id%"

  Scenario: I link the same service twice
    Given there are document rows:
      | document | title   | unitPrice | quantity |
      | I01      | Title 1 | 100       | 1        |
      | I01      | Title 2 | 200       | 2        |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.rows.0.linkedService" field with "%services.PR1.id%"
    And I fill the "document.rows.1.linkedService" field with "%services.PR1.id%"
    And I press "Save"
    Then I should see the "document.rows.0.linkedService" field with "%services.PR1.id%"
    Then I should see the "document.rows.1.linkedService" field with "%services.PR1.id%"

  @javascript
  Scenario: I link a service
    Given there are document rows:
      | document | title   | unitPrice | quantity |
      | I01      | Title 1 | 100       | 1        |
      | I01      | Title 2 | 200       | 2        |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.rows.0.title" field with "PR1"
    And I wait until the typeahead completes
    And I trigger a "click" event on ".tt-selectable:nth-child(1)"
    Then I should see the "document.rows.0.linkedService" field with "%services.PR1.id%"
    And I should see "Service:"
    And I should see the "document.rows.0" fields:
      | unitPrice | 100.00 |
    # This cannot be tested since the HTML source is not changed (only the DOM is)
    # And I should see the "document.rows.0.taxRate" option "10%" selected

