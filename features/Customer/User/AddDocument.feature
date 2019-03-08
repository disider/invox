Feature: User adds a document for a customer
  In order to link a document to a customer
  As a user
  I want to add a document for a customer

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

  Scenario: I add a document for a customer
    Given there is a customer:
      | name      | email                 | company | vatNumber   |
      | Customer1 | customer1@example.com | Acme    | 01234567890 |
    When I visit "/documents/new?customerId=%customers.last.id%"
    Then I should see the "document" fields:
      | linkedCustomer    | %customers.last.id% |
      | customerName      | Customer1           |
      | customerVatNumber | 01234567890         |

  Scenario: I cannot add a document for an undefined customer
    When I try to visit "/documents/new?customerId=-1"
    Then the response status code should be 404

  Scenario: I cannot add a document for a customer I don't own
    Given there is a customer:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Bros    |
    When I try to visit "/documents/new?customerId=%customers.last.id%"
    Then the response status code should be 404
