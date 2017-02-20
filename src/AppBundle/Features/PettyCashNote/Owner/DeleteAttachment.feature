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
    And there is a country:
      | code | name  |
      | IT   | Italy |
    And there is an account:
      | name | company |
      | Bank | Acme    |
    And there is a petty cash note:
      | accountFrom | amount | ref |
      | Bank        | 10.00  | P01 |
    And I am logged as "user@example.com"

  @javascript
  Scenario: I delete an uploaded attachment
    Given I visit "/petty-cash-notes/%pettyCashNotes.last.id%/edit"
    And I drop "test.png" in the "pettyCashNote_attachments_uploader" field
    And I wait until the upload completes
    When I click on "Attachments"
    And I click on "Delete"
    Then I should see 0 "attachment"s

  @javascript
  Scenario: I delete a persisted attachment
    Given there is a petty cash note attachment:
      | fileName | fileUrl  | pettyCashNote |
      | test.png | test.png | P01           |
    And I visit "/petty-cash-notes/%pettyCashNotes.last.id%/edit"
    When I click on "Attachments"
    And I click on "Delete"
    And I press "Save"
    Then I should see 0 "attachment"s
    And no file should be saved into "/uploads/companies/%companies.last.id%/petty-cash-notes/%pettyCashNotes.last.id%/attachments/test.png"
