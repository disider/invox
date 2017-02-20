Feature: User adds attachments for his documents
  In order to add an attachment to a document
  As a user
  I want to upload new files and link them to the document

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a country:
      | code | name  |
      | IT   | Italy |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there are tax rates:
      | name | amount |
      | 10%  | 10     |
      | 22%  | 22     |
    And I am logged as "user@example.com"
    When I visit "/documents/new"

  @javascript
  Scenario: I add an attachment to a new document without persisting the document
    Given I fill the "document" form with:
      | ref   | customerName | customerVatNumber |
      | ID123 | Customer     | 01234567890       |
    And I select the "document.customerCountry" option "Italy"
    And I click on "Attachments"
    When I drop "test.png" in the "document_attachments_uploader" field
    Then I should see 1 "attachment"
    And I should see "test.png"

  @javascript
  Scenario: I add multiple attachments to a new document
    Given I fill the "document" form with:
      | ref   | customerName | customerVatNumber |
      | ID123 | Customer     | 01234567890       |
    And I select the "document.customerCountry" option "Italy"
    When I drop "test.png" in the "document_attachments_uploader" field
    When I drop "test.pdf" in the "document_attachments_uploader" field
    And I wait until the upload completes
    And I press "Save"
    Then I should see 2 "attachment"s
#    And a file should be saved into "/uploads/companies/%companies.last.id%/documents/%documents.last.id%/attachments/test.png"
#    And a file should be saved into "/uploads/companies/%companies.last.id%/documents/%documents.last.id%/attachments/test.pdf"

