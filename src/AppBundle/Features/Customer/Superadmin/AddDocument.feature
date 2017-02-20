Feature: Superadmin adds a document for a customer
  In order to link a document to a customer
  As a superadmin
  I want to add a document for a customer

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there are companies:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I add a document for a customer
    Given there is a customer:
      | name      | email                 | company | vatNumber   |
      | Customer1 | customer1@example.com | Acme    | 01234567890 |
    When I visit "/documents/new?customerId=%customers.last.id%"
    Then I should see the "document" fields:
      | linkedCustomer    | %customers.last.id% |
      | customerName      | Customer1           |
      | customerVatNumber | 01234567890         |
