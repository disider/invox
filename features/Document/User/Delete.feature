Feature: User deletes a document
  In order to get rid of a document
  As a user
  I want to delete a document

  Background:
    Given there are users:
      | email             | password |
      | user1@example.com | secret   |
      | user2@example.com | secret   |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there are quotes:
      | ref | user              | customer              | year | company |
      | Q01 | user1@example.com | customer1@example.com | 2014 | Acme    |
      | Q02 | user2@example.com | customer2@example.com | 2014 | Bros    |
    And I am logged as "user1@example.com"

  Scenario: I delete a document
    When I visit "/documents/%documents.Q01.id%/delete"
    Then I should be on "/quotes"
    And I should see 0 "document"s

  Scenario: I delete an invoice linked to a petty cash note
    Given there is an invoice:
      | user              | customer              | ref | year | company |
      | user1@example.com | customer1@example.com | I01 | 2014 | Acme    |
    And there is an account:
      | name  | company |
      | Bank1 | Acme    |
    And there is a petty cash note:
      | ref | accountFrom | amount |
      | PC1 | Bank1       | 10.00  |
    And there is an invoice linked to a petty cash note:
      | invoice | note | amount |
      | I01     | PC1  | 5.00   |
    When I visit "/documents/%documents.I01.id%/delete"
    Then I should be on "/invoices"
    And I should see 0 "invoice"s

  Scenario: I cannot delete a document I don't own
    When I try to visit "/documents/%documents.Q02.id%/delete"
    Then the response status code should be 403
