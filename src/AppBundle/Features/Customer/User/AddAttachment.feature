Feature: User adds attachments for his customers
  In order to add an attachment to a customer
  As a user
  I want to upload new files and link them to the customer

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
    And I am logged as "user@example.com"
    When I visit "/customers/new"

  @javascript
  Scenario: I add an attachment to a new customer without persisting the customer
    Given I fill the "customer" form with:
      | name | vatNumber   |
      | Bros | 01234567890 |
    And I select the "customer.country" option "Italy"
    When I drop "test.png" in the "customer_attachments_uploader" field
    Then I should see 1 "attachment"
    And I should see "test.png"

  @javascript
  Scenario: I add multiple attachments to a new customer
    Given I fill the "customer" form with:
      | name | vatNumber   |
      | Bros | 01234567890 |
    And I select the "customer.country" option "Italy"
    When I drop "test.png" in the "customer_attachments_uploader" field
    When I drop "test.pdf" in the "customer_attachments_uploader" field
    And I wait until the upload completes
    And I press "Save"
    Then I should see 2 "attachment"s
#    And a file should be saved into "/uploads/companies/%companies.last.id%/customers/%customers.last.id%/attachments/test.png"
#    And a file should be saved into "/uploads/companies/%companies.last.id%/customers/%customers.last.id%/attachments/test.pdf"

