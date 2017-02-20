Feature: User changes the document type
  In order to change the type of a document
  As a user
  I want to choose the document type from a selector

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a customer:
      | name     | email                | company |
      | Customer | customer@example.com | Acme    |
    And I am logged as "user@example.com"

  Scenario: I link a customer
    Given there is a quote:
      | user             | customerName      | ref | year | company |
      | user@example.com | Unlinked customer | 001 | 2014 | Acme    |
    When I visit "/documents/%documents.last.id%/edit"
    And I fill the "document.linkedCustomer" field with "%customers.last.id%"
    And I press "Save"
    Then I should see the "document" fields:
      | linkedCustomer | %customers.last.id% |

  Scenario: I unlink a customer
    Given there is a quote:
      | user             | customer             | ref | year | company |
      | user@example.com | customer@example.com | 001 | 2014 | Acme    |
    When I visit "/documents/%documents.last.id%/edit"
    And I fill the "document.linkedCustomer" field with ""
    And I press "Save"
    Then I should not see the "document" fields values:
      | linkedCustomer | %customers.last.id% |
