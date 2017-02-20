Feature: Owner deletes a petty cash
  In order to get rid of a petty cash
  As a user
  I want to delete a petty cash

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
      | name    | company |
      | Bank1   | Acme    |
      | Credit1 | Acme    |
      | Bank2   | Bros    |
    And I am logged as "owner1@example.com"

  Scenario: I delete a petty cash
    Given there is a petty cash note:
      | accountFrom | amount |
      | Bank1       | 10.00  |
    When I visit "/petty-cash-notes/%pettyCashNotes.last.id%/delete"
    Then I should be on "/petty-cash-notes"
    And I should see 0 "petty-cash-note"

  Scenario: I cannot delete a document I don't own
    Given there is a petty cash note:
      | accountFrom | amount |
      | Bank2       | 10.00  |
    When I try to visit "/petty-cash-notes/%pettyCashNotes.last.id%/delete"
    Then the response status code should be 403
