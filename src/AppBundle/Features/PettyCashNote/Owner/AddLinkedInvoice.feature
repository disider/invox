Feature: Owner adds a petty cash linked to an invoice

  Background:
    Given there are users:
      | email              | password |
      | owner1@example.com | secret   |
      | owner2@example.com | secret   |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there are accounts:
      | name   | company |
      | Bank11 | Acme    |
      | Bank12 | Acme    |
      | Bank21 | Bros    |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there is an order:
      | user               | customer              | ref | company |
      | owner1@example.com | customer1@example.com | O01 | Acme    |
    And there are invoices:
      | ref | user               | customer              | company | linkedOrder |
      | I01 | owner1@example.com | customer1@example.com | Acme    | O01         |
      | I02 | owner2@example.com | customer2@example.com | Bros    |             |
    And there is a tax rate:
      | name | amount |
      | 10%  | 10     |
    And there are document rows:
      | document | title     | unitPrice | taxRate |
      | O01      | Product 1 | 5         | 10      |
      | I01      | Product 1 | 5         | 10      |
    And I am logged as "owner1@example.com"
    When I visit "/petty-cash-notes/new"

  @javascript
  Scenario: I add a petty cash linked to an invoice
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountFrom" option "Bank11"
    And I click on "Add invoice"
    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field and select the "I01" option
    And I fill the "pettyCashNote.invoices.0.amount" field with "5"
    When I press "Save and close"
    Then I should be on "/petty-cash-notes"

#  # This cannot happen anymore
#  @javascript
#  Scenario: I cannot add an invoice linked to a petty cash note without amount
#    Given I fill the "pettyCashNote" form with:
#      | amount |
#      | 10.00  |
#    And I select the "pettyCashNote.accountFrom" option "Bank"
#    And I click on "Add invoice"
#    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field and select the "I01" option
#    And I fill the "pettyCashNote.invoices.0.amount" field with ""
#    When I press "Save and close"
#    Then I should see an "Empty amount" error

  @javascript
  Scenario: Invoice unpaid if its amount is greater than petty cash amount
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountFrom" option "Bank"
    And I click on "Add invoice"
    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field and select the "I01" option
    And I fill the "pettyCashNote.invoices.0.amount" field with "5"
    When I press "Save and close"
    And I visit "/documents/%documents.I01.id%/edit"
    Then I should see "Unpaid"
    When I visit "/documents/%documents.O01.id%/edit"
    Then I should see "Unpaid"

  @javascript
  Scenario: Invoice is paid if its amount is equal to petty cash amount
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountFrom" option "Bank"
    And I click on "Add invoice"
    And I fill the "pettyCashNote.invoices.0.invoiceTitle" field and select the "I01" option
    And I fill the "pettyCashNote.invoices.0.amount" field with "5.5"
    When I press "Save and close"
    And I visit "/documents/%documents.I01.id%/edit"
    Then I should see "Paid"
    And I visit "/documents/%documents.O01.id%/edit"
    Then I should see "Paid"
