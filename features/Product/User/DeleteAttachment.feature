Feature: User adds attachments for his products
  In order to add an attachment to a product
  As a user
  I want to upload new files and link them to the product

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a product:
      | name      | email                | company |
      | Customer1 | product1@example.com | Acme    |
    And I am logged as "user@example.com"

  @javascript
  Scenario: I delete an uploaded attachment
    Given I visit "/products/%products.last.id%/edit"
    And I drop "test.png" in the "product_attachments_uploader" field
    And I wait until the upload completes
    And I click on "Delete"
    Then I should see 0 "attachment"s

  @javascript
  Scenario: I delete a persisted attachment
    Given there is a product attachment:
      | fileName | fileUrl  | product   |
      | test.png | test.png | Customer1 |
    And I visit "/products/%products.last.id%/edit"
    When I click on "Delete"
    Then I should see 0 "attachment"s
    When I press "Save"
    And I should see 0 "attachment"s
    And no file should be saved into "/uploads/companies/%companies.last.id%/products/%products.last.id%/attachments/test.png"
