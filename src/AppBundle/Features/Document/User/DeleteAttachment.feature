Feature: User adds attachments for his documents
  In order to add an attachment to a document
  As a user
  I want to upload new files and link them to the document

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
    And there is a customer:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
    And there is a quote:
      | user              | customer              | ref | year | company |
      | user1@example.com | customer1@example.com | D01 | 2014 | Acme    |
    And I am logged as "user@example.com"

  @javascript
  Scenario: I delete an uploaded attachment
    Given I visit "/documents/%documents.last.id%/edit"
    And I drop "test.png" in the "document_attachments_uploader" field
    And I wait until the upload completes
    When I click on "Attachments"
    And I click on "Delete"
    Then I should see 0 "attachment"s

  @javascript
  Scenario: I delete a persisted attachment
    Given there is a document attachment:
      | fileName | fileUrl  | document |
      | test.png | test.png | D01      |
    And I visit "/documents/%documents.last.id%/edit"
    When I click on "Attachments"
    And I click on "Delete"
    And I press "Save"
    Then I should see 0 "attachment"s
    And no file should be saved into "/uploads/companies/%companies.last.id%/documents/%documents.last.id%/attachments/test.png"
