Feature: Accountant lists a company credit notes
  In order to access the document details of I company I account
  As an accountant
  I can list all my credit notes

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | user1@example.com      |
      | user2@example.com      |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And I am logged as "accountant@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I view a list of credit notes
    Given there are credit notes:
      | ref | user              | customer              | company |
      | 001 | user1@example.com | customer1@example.com | Acme    |
      | 002 | user1@example.com | customer1@example.com | Acme    |
    When I visit "/credit-notes"
    Then I should see 2 "document" rows

  Scenario: I can only show credit notes
    Given there are credit notes:
      | user              | customer              | ref | year | company |
      | user1@example.com | customer1@example.com | 001 | 2014 | Acme    |
      | user1@example.com | customer1@example.com | 002 | 2014 | Acme    |
    When I visit "/credit-notes"
    Then I should see 0 links with class ".edit"
    And I should see 0 links with class ".delete"
    And I should see 0 links with class ".copy"
    And I should see 2 links with class ".view"

  Scenario: I view no credit notes I don't own
    Given there is an invoice:
      | user              | customer              | ref | year | company |
      | user2@example.com | customer2@example.com | 001 | 2014 | Bros    |
    When I visit "/credit-notes"
    Then I should see 0 "document"s
