Feature: User links a recurrence to an invoice
  In order to link recurrence and invoices
  As a user
  I want to select the linked recurrence

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
      | name | email                 | company |
      | C1   | customer1@example.com | Acme    |
      | C2   | customer2@example.com | Bros    |
      | C3   | customer3@example.com | Acme    |
    And there are tax rates:
      | name | amount |
      | 0%   | 0      |
      | 10%  | 10     |
      | 22%  | 22     |
    And there is a recurrence:
      | content | customer | company |
      | R1      | C1       | Acme    |
    And I am logged as "user1@example.com"

  Scenario: I see a recurrence
    Given there is an invoice:
      | user              | customer              | ref | company | recurrence |
      | user1@example.com | customer1@example.com | I01 | Acme    | R1         |
    When I visit "/documents/%documents.I01.id%/edit"
    Then I should see the "document.recurrence" field with "%recurrences.R1.id%"

  Scenario: I link an invoice
    Given there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.recurrence" field with "%recurrences.R1.id%"
    And I press "Save"
    Then I should see the "document.recurrence" field with "%recurrences.R1.id%"

  Scenario: I cannot link a recurrence I don't own
    Given there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    And there is a recurrence:
      | content | customer | company |
      | R2      | C2       | Bros    |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.recurrence" field with "%recurrences.R2.id%"
    And I press "Save"
    Then I should see an "Invalid recurrence" error

  Scenario: I cannot link a recurrence if is the other customer
    Given there is an invoice:
      | user              | customer              | ref | company |
      | user1@example.com | customer1@example.com | I01 | Acme    |
    And there is a recurrence:
      | content | customer | company |
      | R3      | C3       | Acme    |
    When I visit "/documents/%documents.I01.id%/edit"
    And I fill the "document.linkedCustomer" field with "%customers.C1.id%"
    And I fill the "document.recurrence" field with "%recurrences.R3.id%"
    And I press "Save"
    Then I should see an "Invalid recurrence" error
