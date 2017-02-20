Feature: Owner deletes a petty cash
  In order to delete a petty cash
  As a user
  I want to delete petty cash details

  Background:
    Given there are users:
      | email              | password |
      | owner1@example.com | secret   |
    And there is a company:
      | name | owner              |
      | Acme | owner1@example.com |
    And there are accounts:
      | name  | company |
      | Bank1 | Acme    |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
    And there is an invoice:
      | ref | user               | customer              | company |
      | I11 | owner1@example.com | customer1@example.com | Acme    |
    And there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And there is a document row:
      | document | title     | unitPrice | taxRate |
      | I11      | Product 1 | 5         | 0       |
    And there are petty cash notes:
      | ref | accountFrom | amount |
      | PC1 | Bank1       | 10.00  |
      | PC2 | Bank1       | 10.00  |
    And there are invoices linked to petty cash notes:
      | invoice | note | amount |
      | I11     | PC1  | 5.00   |
    And I am logged as "owner1@example.com"

  @javascript
  Scenario: I delete linked invoices
    Given I visit "/petty-cash-notes/%pettyCashNotes.PC1.id%/edit"
    When I click on "Delete"
    Then I should see 0 "invoice"

