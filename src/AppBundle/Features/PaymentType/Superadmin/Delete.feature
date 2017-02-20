Feature: Superadmin can delete a payment type
  In order to delete a payment type
  As a superadmin
  I want to delete a payment type

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a payment type:
      | name                 | days | endOfMonth |
      | 30 days end of month | 30   | false      |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a payment type
    When I visit "/payment-types/%paymentTypes.last.id%/delete"
    Then I should be on "/payment-types"
    And I should see 0 "payment-type"
