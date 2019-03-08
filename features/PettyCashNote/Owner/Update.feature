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
      | name    | company |
      | Bank1   | Acme    |
      | Credit1 | Acme    |
      | Bank2   | Bros    |
    And there are petty cash notes:
      | ref | accountFrom | amount |
      | PC1 | Bank1       | 10.00  |
      | PC2 | Bank2       | 10.00  |
    And I am logged as "owner1@example.com"
    And I visit "/petty-cash-notes/%pettyCashNotes.PC1.id%/edit"

  Scenario: I can view a petty cash details
    Then I should see the "pettyCashNote" fields:
      | ref    | PC1   |
      | amount | 10.00 |
    And I should see the "pettyCashNote.accountFrom" option "Bank1" selected
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I update a petty cash
    Given I fill the "pettyCashNote" form with:
      | ref | amount |
      | 123 | 10.00  |
    And I select the "pettyCashNote.accountFrom" option "Bank1"
    And I select the "pettyCashNote.accountTo" option "Credit1"
    And I press "Save"
    Then I should see the "pettyCashNote" fields:
      | ref    | 123   |
      | amount | 10.00 |
    And I should see "successfully updated"
    And I should see the "pettyCashNote.accountTo" option "Credit1" selected

  Scenario: I cannot edit a petty cash note I don't own
    When I try to visit "/petty-cash-notes/%pettyCashNotes.PC2.id%/edit"
    Then the response status code should be 403

