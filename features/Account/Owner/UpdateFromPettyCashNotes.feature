Feature: User updates a petty cash
  In order to update account balances
  As a user
  I want to modify petty cash notes

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there are accounts:
      | name   | company |
      | Bank   | Acme    |
      | Credit | Acme    |
    And there are petty cash notes:
      | ref | accountTo | amount |
      | PC1 | Bank      | 10.00  |
      | PC2 | Bank      | 10.00  |
    And I am logged as "user@example.com"

  Scenario: I see the updated accounts
    When I visit "/accounts"
    Then I should see the "account" rows:
      | name   | initial-amount | current-amount |
      | Bank   | 0.00           | 20.00          |
      | Credit | 0.00           | 0.00           |

  Scenario: I see the modified current amount after adding a petty cash note
    Given I visit "/petty-cash-notes/new"
    And I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountTo" option "Bank"
    And I press "Save and close"
    When I visit "/accounts"
    Then I should see the "account" rows:
      | name | initial-amount | current-amount |
      | Bank | 0.00           | 30.00          |

  Scenario: I see the modified current amount after removing a petty cash note
    Given I visit "/petty-cash-notes/%pettyCashNotes.last.id%/delete"
    When I visit "/accounts"
    Then I should see the "account" rows:
      | name | initial-amount | current-amount |
      | Bank | 0.00           | 10.00          |

  Scenario: I see the modified current amount after updating a petty cash note
    Given I visit "/petty-cash-notes/%pettyCashNotes.last.id%/edit"
    And I fill the "pettyCashNote" form with:
      | amount |
      | 50.00  |
    And I press "Save and close"
    When I visit "/accounts"
    Then I should see the "account" rows:
      | name | initial-amount | current-amount |
      | Bank | 0.00           | 60.00          |

  Scenario: I see the modified current amount after changing the petty cash note account
    Given I visit "/petty-cash-notes/%pettyCashNotes.last.id%/edit"
    And I select the "pettyCashNote.accountTo" option "Credit"
    And I press "Save and close"
    When I visit "/accounts"
    Then I should see the "account" rows:
      | name   | initial-amount | current-amount |
      | Bank   | 0.00           | 10.00          |
      | Credit | 0.00           | 10.00          |
