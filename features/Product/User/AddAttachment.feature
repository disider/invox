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
    And I am logged as "user@example.com"
    When I visit "/products/new"

  @javascript
  Scenario: I add an attachment to a new product without persisting the product
    Given I fill the "product" fields with:
      | name | Product1 |
      | code | PR1      |
    When I drop "test.png" in the "product_attachments_uploader" field
    Then I should see 1 "attachment"
    And I should see "test.png"

  @javascript
  Scenario: I add multiple attachments to a new product
    Given I fill the "product" fields with:
      | name | Product1 |
      | code | PR1      |
    When I drop "test.png" in the "product_attachments_uploader" field
    When I drop "test.pdf" in the "product_attachments_uploader" field
    And I wait until the upload completes
    And I press "Save"
    Then I should see 2 "attachment"s
#    And a file should be saved into "/uploads/companies/%companies.last.id%/products/%products.last.id%/attachments/test.png"
#    And a file should be saved into "/uploads/companies/%companies.last.id%/products/%products.last.id%/attachments/test.pdf"

