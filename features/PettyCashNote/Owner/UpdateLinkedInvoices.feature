Feature: Owner updates a petty cash
  In order to modify a petty cash
  As a user
  I want to edit petty cash details

  Background:
    Given there are users:
      | email              |
      | owner1@example.com |
      | owner2@example.com |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there are accounts:
      | name  | company |
      | Bank1 | Acme    |
      | Bank2 | Acme    |
      | Bank1 | Bros    |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there are invoices:
      | ref | user               | customer              | company | direction |
      | I11 | owner1@example.com | customer1@example.com | Acme    | outgoing  |
      | I12 | owner1@example.com | customer1@example.com | Acme    | incoming  |
      | I13 | owner1@example.com | customer1@example.com | Acme    | outgoing  |
      | I21 | owner2@example.com | customer2@example.com | Bros    | outgoing  |
    And there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And there are document rows:
      | document | title     | unitPrice | taxRate |
      | I11      | Product 1 | 10        | 0       |
      | I12      | Product 1 | 10        | 0       |
      | I13      | Product 1 | 10        | 0       |
      | I21      | Product   | 10        | 0       |
    And there are petty cash notes:
      | ref | accountFrom | accountTo | amount |
      | PC1 |             | Bank1     | 10.00  |
      | PC2 | Bank1       |           | 10.00  |
    And there are invoices linked to petty cash notes:
      | invoice | note | amount |
      | I11     | PC1  | 5.00   |
      | I12     | PC2  | 5.00   |
    And I am logged as "owner1@example.com"
#
#  Scenario: I cannot add any more linked invoices
#    When I visit "/petty-cash-notes/%pettyCashNotes.PC1.id%/edit"
#    Then I should not see "Add invoice"

  @javascript
  Scenario: I cannot add an invoice amount higher than current amount or invoice amount
    When I visit "/petty-cash-notes/%pettyCashNotes.PC2.id%/edit"
    And I select the "pettyCashNote.accountFrom" option "Bank"
    And I click on "Add invoice"
    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field and select the "I13" option
    And I fill the "pettyCashNote.invoices.0.amount" field with "20"
    And I press "Save"
    Then I should see an "cannot exceed the note total amount" error
    Then I should see an "cannot exceed the invoice available amount" error

  @javascript
  Scenario: I cannot add an invoice amount higher than available invoice amount
    And there are petty cash notes:
      | ref | accountFrom | amount |
      | PC3 | Bank1       | 10.00  |
    And there are invoices linked to petty cash notes:
      | invoice | note | amount |
      | I13     | PC3  | 5.00   |
    When I visit "/petty-cash-notes/%pettyCashNotes.PC2.id%/edit"
    And I select the "pettyCashNote.accountFrom" option "Bank"
    And I click on "Add invoice"
    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field and select the "I13" option
    And I fill the "pettyCashNote.invoices.0.amount" field with "6"
    And I press "Save"
    Then I should see an "cannot exceed the invoice available amount" error

  @javascript
  Scenario: I cannot add a quote to a note
    Given there is a quote:
      | ref | user               | customer              | company |
      | Q11 | owner1@example.com | customer1@example.com | Acme    |
    When I visit "/petty-cash-notes/%pettyCashNotes.PC2.id%/edit"
    And I select the "pettyCashNote.accountFrom" option "Bank"
    And I click on "Add invoice"
    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field with "%documents.Q11.id%" and wait until the typeahead completes
    Then I should see "No documents found"

  @javascript
  Scenario: I cannot add an invoice I don't own to a note
    When I visit "/petty-cash-notes/%pettyCashNotes.PC2.id%/edit"
    And I select the "pettyCashNote.accountFrom" option "Bank"
    And I click on "Add invoice"
    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field with "%documents.I11.id%" and wait until the typeahead completes
    Then I should see "No documents found"

  @javascript
  Scenario: I cannot add an invoice already balanced by other notes
    When I visit "/petty-cash-notes/%pettyCashNotes.PC2.id%/edit"
    And I select the "pettyCashNote.accountFrom" option "Bank"
    And I click on "Add invoice"
    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field with "%documents.I11.id%" and wait until the typeahead completes
    Then I should see "No documents found"

  Scenario: I cannot add a petty cash linked to an invoice if it's a tranfer
    Given I visit "/petty-cash-notes/%pettyCashNotes.PC1.id%/edit"
    And I select the "pettyCashNote.accountFrom" option "Bank1"
    And I select the "pettyCashNote.accountTo" option "Bank2"
    When I press "Save and close"
    Then I should be on "/petty-cash-notes/%pettyCashNotes.PC1.id%/edit"
    And I should see a "Cannot link invoices to a transfer petty cash note" global error

  Scenario: I cannot add an incoming invoice to an income petty cash note
    Given I visit "/petty-cash-notes/%pettyCashNotes.PC1.id%/edit"
    And I fill the "pettyCashNote.invoices.0.invoice" field with "%documents.I12.id%"
    When I press "Save"
    Then I should be on "/petty-cash-notes/%pettyCashNotes.PC1.id%/edit"
    And I should see a "Cannot link an incoming invoice to an income petty cash note" global error

  Scenario: I cannot add an outgoing invoice to an outcome petty cash note
    Given I visit "/petty-cash-notes/%pettyCashNotes.PC2.id%/edit"
    And I fill the "pettyCashNote.invoices.0.invoice" field with "%documents.I11.id%"
    When I press "Save and close"
    Then I should be on "/petty-cash-notes/%pettyCashNotes.PC2.id%/edit"
    And I should see a "Cannot link an outgoing invoice to an outcome petty cash note" global error
