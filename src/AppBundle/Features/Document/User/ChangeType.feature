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
    And there is a quote:
      | user             | customer             | ref | year | company |
      | user@example.com | customer@example.com | 001 | 2014 | Acme    |
    And I am logged as "user@example.com"
    And I visit "/documents/%documents.last.id%/edit"

# TODO: should be tested with javascript
#  Scenario: I change the document type
#    Given I should see the "document.type" option "Quote" selected
#    When I select the "document.type" option "invoice"
#    And I should not see the "document.direction" field
#    And I press "Change type"
#    Then I should see the "document.type" option "Invoice" selected
#    And I should see the "Outgoing" field checked
