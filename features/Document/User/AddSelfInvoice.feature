Feature: User adds adds a self-invoice
  In order to handle self-invoices
  As a user
  I want to mark an invoice as "self invoice"

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there are tax rates:
      | name | amount |
      | 10%  | 10     |
      | 22%  | 22     |
    And there is a payment type:
      | name                 | days | endOfMonth |
      | 30 days end of month | 30   | false      |
    And I am logged as "user@example.com"
    And I visit "/documents/new?type=invoice"

  Scenario: I can add a self invoice
    Then I should see the "document.selfInvoice" field unchecked
    And I can press "Save"

  Scenario: I add a self invoice
    Given I fill the "document" form with:
      | title   | ref | customerName | customerVatNumber |
      | A quote | I01 | Customer     | 01234567890       |
    And I check the "document[direction]" radio button with "incoming" value
    And I fill the "document.rows.0" form with:
      | title     | unitPrice | quantity |
      | Product 1 | 10        | 2        |
    And I select the "document.rows.0.taxRate" option "10%"
    And I fill the "document.validUntil" field with "01/01/2099"
    And I check the "document.selfInvoice" field
    When I press "Save"
    Then the "%documents.I01.status%" entity property should be "paid"

# TODO: Should be tested with javascript
#  Scenario: I cannot mark as self-invoice a non invoice
#    Given I visit "/documents/new?type=quote"
#    Then I should not see the "document.selfInvoice" field
