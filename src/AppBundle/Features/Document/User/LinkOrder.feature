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
      | 22%  | 22     |
    And there is a payment type:
      | name                 | days | endOfMonth |
      | 30 days end of month | 30   | false      |
    And I am logged as "user1@example.com"

  Scenario: I see a linked order
    Given there is an order:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | O01 | Acme    |
    And there is an invoice:
      | user              | customer              | ref | company | linkedOrder |
      | user1@example.com | customer1@example.com | I01 | Acme    | O01         |
    When I visit "/documents/%documents.I01.id%/edit"
    Then I should see the "document.linkedOrder" field with "%documents.O01.id%"

  Scenario: I link an order
    Given there is an order:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | O01 | Acme    |
    And there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.linkedOrder" field with "%documents.O01.id%"
    And I press "Save"
    Then I should see the "document.linkedOrder" field with "%documents.O01.id%"

  Scenario: Order is unpaid if its amount is greater than invoice amount
    Given there is an order:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | O01 | Acme    |
    And there are document rows:
      | document | title     | unitPrice | quantity | taxRate |
      | O01      | Product 1 | 100       | 2        | 10      |
      | O01      | Product 2 | 200       | 4        | 22      |
    And there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.linkedOrder" field with "%documents.O01.id%"
    And I press "Save"
    Then I should see the "document.linkedOrder" field with "%documents.O01.id%"
    And the "%documents.O01.status%" entity property should be "unpaid"

  Scenario: Order is paid if its amount is equal to invoice amount
    Given there is an order:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | O01 | Acme    |
    And there is an invoice:
      | user              | customer              | ref | company | direction |
      | user1@example.com | customer1@example.com | I01 | Acme    | outgoing  |
    And there are document rows:
      | document | title     | unitPrice |
      | O01      | Product 1 | 100       |
      | I01      | Product 1 | 100       |
    And there is an account:
      | name  | company |
      | Bank1 | Acme    |
    And there is a petty cash note:
      | ref | accountTo | amount |
      | PC1 | Bank1     | 100.00 |
    And there is an invoice linked to a petty cash note:
      | invoice | note | amount |
      | I01     | PC1  | 100.00 |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.linkedOrder" field with "%documents.O01.id%"
    And I press "Save"
    Then I should see the "document.linkedOrder" field with "%documents.O01.id%"
    And the "%documents.O01.status%" entity property should be "paid"

# TODO: should be tested with javascript
#
#  Scenario: I cannot link an order to an order
#    Given there are orders:
#      | user              | customer              | ref | company |
#      | user1@example.com | customer1@example.com | O01 | Acme    |
#    When I visit "/documents/%documents.O01.id%/edit"
#    Then I should not see the "document.linkedOrder" field

  Scenario: I cannot link a different type of document
    Given there is a quote:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | Q01 | Acme    |
    And there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.linkedOrder" field with "%documents.Q01.id%"
    And I press "Save"
    Then I should see an "Invalid document type" error

  Scenario: I cannot link an order I don't own
    Given there is an order:
      | user              | customer              | ref | company |
      | user2@example.com | customer2@example.com | Q02 | Bros    |
    And there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.linkedOrder" field with "%documents.Q02.id%"
    And I press "Save"
    Then I should see an "Invalid order" error
