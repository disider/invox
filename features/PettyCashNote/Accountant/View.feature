Feature: Accountant views a document
  In order to view a document
  As an accountant
  I want to view all document details

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | owner1@example.com     |
      | owner2@example.com     |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And there are accounts:
      | name  | company |
      | Bank1 | Acme    |
      | Bank2 | Bros    |
    And I am logged as "accountant@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I can view a petty cash note details
    Given there is a petty cash note:
      | accountFrom | amount |
      | Bank1       | 10.00  |
    When I visit "/petty-cash-notes/%pettyCashNotes.last.id%/view"
    Then I should see the "petty-cash-note" details:
      | amount | 10.00 |

  Scenario: I cannot view details for a petty cash note I don't account
    Given there is a petty cash note:
      | accountFrom | amount |
      | Bank2       | 10.00  |
    When I try to visit "/petty-cash-notes/%pettyCashNotes.last.id%/view"
    Then the response status code should be 403
