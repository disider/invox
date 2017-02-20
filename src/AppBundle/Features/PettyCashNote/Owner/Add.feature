Feature: Owner adds a petty cash note
  In order to add a petty cash note
  As a user
  I want to add a petty cash filling a form

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
      | Bank2 | Bros    |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there are invoices:
      | ref | user               | customer              | company |
      | 001 | owner1@example.com | customer1@example.com | Acme    |
      | 002 | owner2@example.com | customer2@example.com | Bros    |
    And there is a tax rate:
      | name | amount |
      | 10%  | 10     |
    And there are document rows:
      | document | title     | unitPrice | taxRate |
      | 001      | Product 1 | 5         | 10      |
    And I am logged as "owner1@example.com"
    When I visit "/petty-cash-notes/new"

  Scenario: I can add a petty cash note
    Then I can press "Save"
    And I can press "Save and close"
    And I should not see the "pettyCashNote.accountFrom" option "Bank2"
    And I should not see the "pettyCashNote.accountTo" option "Bank2"

  Scenario: I add an incoming petty cash note
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountFrom" option "Bank"
    When I press "Save and close"
    Then I should be on "/petty-cash-notes"

  Scenario: I add a localized incoming petty cash note
    When I visit "/it/petty-cash-notes/new"
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10,00  |
    And I select the "pettyCashNote.accountFrom" option "Bank"
    When I press "Salva"
    Then I should see the "pettyCashNote.amount" field with "10,00"

  Scenario: I add an outgoing petty cash note
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountTo" option "Bank"
    When I press "Save and close"
    Then I should be on "/petty-cash-notes"

  Scenario: I cannot add a negative petty cash note
    Given I fill the "pettyCashNote" form with:
      | amount |
      | -10.00 |
    When I press "Save"
    Then I should see an "Amount must be greater than 0" error

  Scenario: I cannot add a petty cash note transfer between the same account
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountFrom" option "Bank1"
    And I select the "pettyCashNote.accountTo" option "Bank1"
    When I press "Save"
    Then I should see a "Cannot transfer to the same account" global error
