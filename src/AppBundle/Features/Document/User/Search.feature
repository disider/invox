Feature: User searches a document
  In order to search for a document
  As a user
  I want to search for documents

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are accounts:
      | name  | company |
      | Bank1 | Acme    |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    Given there are quotes:
      | ref | user              | customer              | company | title        |
      | 001 | user1@example.com | customer1@example.com | Acme    | First quote  |
      | 002 | user1@example.com | customer1@example.com | Acme    | Second quote |
    And there are invoices:
      | ref | user              | customer              | company |
      | 001 | user1@example.com | customer1@example.com | Acme    |
      | 002 | user1@example.com | customer1@example.com | Acme    |
    And there are orders:
      | ref | user              | customer              | company |
      | 001 | user1@example.com | customer1@example.com | Acme    |
      | 002 | user1@example.com | customer1@example.com | Acme    |
    And there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And I am logged as "user1@example.com"

  Scenario: I can search for a document
    When I visit "/documents/search?term=001"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "documents" property should contain 3 item

  Scenario: I can search for a document by customer name
    When I visit "/documents/search?term=Cust"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "documents" property should contain 6 item

  Scenario: I can search for a document by title
    When I visit "/documents/search?term=First"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "documents" property should contain 1 item

  Scenario: I can search for an order
    When I visit "/documents/search?term=001&type=order"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "documents" property should contain 1 item

  Scenario: I can search for unpaid invoices for a petty cash note (both are paid, only one should be returned)
    Given there are invoices:
      | ref | user              | customer              | company |
      | I03 | user1@example.com | customer1@example.com | Acme    |
      | I04 | user1@example.com | customer1@example.com | Acme    |
    And there are document rows:
      | document | title     | unitPrice | taxRate |
      | I03      | Product 1 | 5         | 0       |
      | I04      | Product 1 | 5         | 0       |
    And there are petty cash notes:
      | ref | accountFrom | amount |
      | PC1 | Bank1       | 10.00  |
      | PC2 | Bank1       | 10.00  |
    And there are invoices linked to petty cash notes:
      | invoice | note | amount |
      | I03     | PC1  | 5.00   |
      | I04     | PC2  | 5.00   |
    When I visit "/documents/search?term=I0&currentNoteId=%pettyCashNotes.last.id%&type=invoice"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "documents" property should contain 1 item
    And the "documents.0.unpaidAmount" property should equal "5.00"

  Scenario: I can search for unpaid invoices
    Given there are invoices:
      | ref | user              | customer              | company |
      | I03 | user1@example.com | customer1@example.com | Acme    |
      | I04 | user1@example.com | customer1@example.com | Acme    |
    And there are document rows:
      | document | title     | unitPrice | taxRate |
      | I03      | Product 1 | 5         | 0       |
      | I04      | Product 1 | 5         | 0       |
    And there is a petty cash note:
      | ref | accountFrom | amount |
      | PC1 | Bank1       | 10.00  |
    And there are invoices linked to petty cash notes:
      | invoice | note | amount |
      | I04     | PC1  | 5.00   |
    When I visit "/documents/search?term=I0&type=invoice&status=unpaid"
    Then the response status code should be 200
    And there should be response headers with:
      | Content-Type | application/json |
    And the "documents" property should contain 1 item
    And the "documents.0.unpaidAmount" property should equal "5.00"
