Feature: User adds attachments for his petty cash notes
  In order to add an attachment to a petty cash note
  As a user
  I want to upload new files and link them to the petty cash note

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is an account:
      | name | company |
      | Bank | Acme    |
    And I am logged as "user@example.com"
    When I visit "/petty-cash-notes/new"

  @javascript
  Scenario: I add an attachment to a new petty cash note without persisting the petty cash note
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountFrom" option "Bank"
    When I drop "test.png" in the "pettyCashNote_attachments_uploader" field
    And I click on "Attachments"
    Then I should see 1 "attachment"
    And I should see "test.png"

  @javascript
  Scenario: I add multiple attachments to a new petty cash note
    Given I fill the "pettyCashNote" form with:
      | amount |
      | 10.00  |
    And I select the "pettyCashNote.accountFrom" option "Bank"
    When I drop "test.png" in the "pettyCashNote_attachments_uploader" field
    When I drop "test.pdf" in the "pettyCashNote_attachments_uploader" field
    And I wait until the upload completes
    And I press "Save"
    Then I should see 2 "attachment"s

