Feature: User links a credit note to an invoice
  In order to link credit notes and invoices
  As a user
  I want to select the linked credit note

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
    And I am logged as "user1@example.com"

  Scenario: I see a linked credit note
    Given there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    And there is a credit note:
      | user              | customer              | ref | company | linkedInvoice |
      | user1@example.com | customer1@example.com | C01 | Acme    | I01           |
    When I visit "/documents/%documents.C01.id%/edit"
    Then I should see the "document.linkedInvoice" field with "%documents.I01.id%"

  Scenario: I link an invoice
    Given there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    And there is a credit note:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | C01 | Acme    |
    When I visit "/documents/%documents.C01.id%/edit"
    And I fill the "document.linkedInvoice" field with "%documents.I01.id%"
    And I press "Save"
    Then I should see the "document.linkedInvoice" field with "%documents.I01.id%"

  Scenario: Invoice is paid if there is a linked credit note with same amount
    Given there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    And there is a credit note:
      | user              | customer              | ref | company | linkedInvoice |
      | user1@example.com | customer1@example.com | C01 | Acme    | I01           |
    And there are document rows:
      | document | title     | unitPrice | quantity | taxRate |
      | I01      | Product 1 | 100       | 2        | 10      |
      | C01      | Product 1 | 100       | 2        | 10      |
    Then the "%documents.I01.status%" entity property should be "paid"

  Scenario: I cannot link a different type of document
    Given there is a quote:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | Q01 | Acme    |
    And there is a credit note:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | C01 | Acme    |
    When I visit "/documents/%documents.C01.id%/edit"
    And I fill the "document.linkedOrder" field with "%documents.Q01.id%"
    And I press "Save"
    Then I should see an "Invalid document type" error

  Scenario: I cannot link an invoice I don't own
    Given there is an invoice:
      | user              | customer              | ref | company |
      | user2@example.com | customer2@example.com | I02 | Bros    |
    And there is a credit note:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | C01 | Acme    |
    When I visit "/documents/%documents.C01.id%/edit"
    And I fill the "document.linkedInvoice" field with "%documents.I02.id%"
    And I press "Save"
    Then I should see an "Invalid invoice" error
