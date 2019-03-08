Feature: User adds a document
  In order to add a document
  As a user
  I want to add a document filling a form

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            | documentTypes  |
      | Acme | user@example.com | invoice, quote |
    And there is a customer:
      | name      | email                 | company | language |
      | Customer1 | customer1@example.com | Acme    | it       |
      | Customer2 | customer2@example.com | Acme    | en       |
    And there are tax rates:
      | name | amount |
      | 10%  | 10     |
      | 22%  | 22     |
    And I am logged as "user@example.com"
    When I visit "/documents/new"

  Scenario: I can add a document
    Then I should see the "Quote" field checked
    And I should see the "document[type]" radio "invoice"
    And I should see the "document[type]" radio "quote"
    And I should not see the "document[type]" radio "credit_note"
    And I should not see the "document[type]" radio "delivery_note"
    And I should not see the "document[type]" radio "order"
    And I should not see the "document[type]" radio "receipt"
    And I should see the "document" fields:
      | companyName | Acme |
    And I can press "Save"
    And I can press "Save and close"
    And I should see 1 "document-row"

  Scenario: I add a document for an existing customer
    Given I fill the "document" form with:
      | title   | ref   | internalRef | year | linkedCustomer      | customerName | customerVatNumber | content          | language | costCenters |
      | A quote | ID123 | 001         | 2015 | %customers.last.id% | Customer     | 01234567890       | <h1>Content</h1> | it       | Expenses    |
    And I fill the "document.rows.0" form with:
      | title     | unitPrice | quantity | description           | discount |
      | Product 1 | 10        | 2        | Product 1 description | 10       |
    And I check the "document.rows.0.discountPercent" field
    And I select the "document.rows.0.taxRate" option "10%"
    When I press "Save and close"
    Then I should be on "/quotes"
    And I should see the "document" rows:
      | ref   | type  | gross-total |
      | ID123 | quote | 19.80       |

  Scenario: I see the document totals
    Given I fill the "document" form with:
      | ref | year | linkedCustomer |
      | 001 | 2015 | 1              |
    And I fill the "document.rows.0" form with:
      | title     | unitPrice | quantity | description           |
      | Product 1 | 10        | 2        | Product 1 description |
    And I select the "document.rows.0.taxRate" option "10%"
    When I press "Save"
    Then I should see the "document" details:
      | taxes-1000  | 2.00  |
      | net-total   | 20.00 |
      | gross-total | 22.00 |

  Scenario: I can add document numbers with thousand separators
    Given I fill the "document" form with:
      | ref | year | customerName  | customerVatNumber | discount | rounding |
      | 001 | 2015 | Customer name | 01234567890       | 1,000.00 | 1,000.00 |
    And I fill the "document.rows.0" form with:
      | title     | unitPrice | quantity | discount |
      | Product 1 | 1,000.00  | 1,000.00 | 1,000.00 |
    When I press "Save"
    Then I should see the "document" fields:
      | ref               | 001           |
      | year              | 2015          |
      | customerName      | Customer name |
      | customerVatNumber | 01234567890   |
      | discount          | 1,000.00      |
      | rounding          | 1,000.00      |
    And I should see the "document.rows.0" fields:
      | title     | Product 1 |
      | unitPrice | 1,000.00  |
      | quantity  | 1,000.00  |
      | discount  | 1,000.00  |
    And I should see the "document.rows.0.discountPercent" field unchecked

  Scenario: I add a document for an unknown customer
    Given I fill the "document" form with:
      | ref | year | customerName   | customerVatNumber |
      | 001 | 2015 | First customer | 01234567890       |
    And I fill the "document.rows.0" form with:
      | title     | unitPrice | quantity | description           |
      | Product 1 | 10        | 1        | Product 1 description |
    And I select the "document.rows.0.taxRate" option "10%"
    When I press "Save and close"
    Then I should be on "/quotes"
    And I should see "11.00"
    And I visit "/customers"
    And I should see 2 "customer"s

  Scenario: I add a document for an unknown customer and add the customer
    Given I fill the "document" form with:
      | ref | year | customerName   | customerVatNumber |
      | 001 | 2015 | First customer | 01234567890       |
    And I check the "document.addNewCustomer" field
    And I fill the "document.rows.0" form with:
      | title     | unitPrice |
      | Product 1 | 10        |
    When I press "Save and close"
    Then I should be on "/quotes"
    And I visit "/customers"
    Then I should see 3 "customer"s

  Scenario: I cannot add a document without mandatory details
    Given I fill the "document" form with:
      | ref |
      |     |
    When I press "Save and close"
    Then I should be on "/documents/new"
    And I should see an "Empty ref" error

  Scenario: I cannot add an invoice without mandatory details
    Given I select the "document.type" option "invoice"
    And I fill the "document.issuedAt" field with ""
    And I fill the "document.validUntil" field with ""
    When I press "Save and close"
    Then I should be on "/documents/new"
    And I should see an "Empty issue date" error
    And I should see an "Empty expiration date" error
    And I should see an "Empty direction" error

  Scenario: I cannot add a document for an unknown customer
    Given I fill the "document" form with:
      | ref | year | linkedCustomer |
      | 001 | 2015 | -1             |
    When I press "Save and close"
    Then I should be on "/documents/new"
    And I should see an "Unknown customer" error

  Scenario: I cannot add a document with empty customer details
    Given I fill the "document" form with:
      | ref | year |
      | 001 | 2015 |
    When I press "Save and close"
    Then I should be on "/documents/new"
    And I should see an "Empty name" error
    And I should see an "Empty VAT number" error

  Scenario: I cannot add a document with a new customer and an invalid VAT number
    Given I fill the "document" form with:
      | ref | year | customerName   | customerVatNumber |
      | 001 | 2015 | First customer | WRONGVAT          |
    Then I should be on "/documents/new"
    When I press "Save and close"
    And I should see an "Invalid VAT number" error

  Scenario: I cannot add a document without row missing details
    Given I fill the "document" form with:
      | ref | year | linkedCustomer |
      | 001 | 2015 | 1              |
    When I press "Save and close"
    And I should see an "Empty title" error
    And I should see an "Empty unit price" error

  Scenario: I cannot upload an invalid image type
    Given I fill the "document" form with:
      | title   | ref   | internalRef | year | linkedCustomer      | customerName | customerVatNumber | content          | language | costCenters |
      | A quote | ID123 | 001         | 2015 | %customers.last.id% | Customer     | 01234567890       | <h1>Content</h1> | it       | Expenses    |
    And I select the "document.rows.0.taxRate" option "10%"
    And I upload "test.pdf" in the "document.companyLogo" field
    When I press "Save and close"
    And I should see a "Invalid image type" error
