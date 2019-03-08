Feature: User cannot access payment type pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a payment type:
      | name                 | days | endOfMonth |
      | 30 days end of month | 30   | false      |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                        |
      | /payment-types                               |
      | /payment-types/new                           |
      | /payment-types/%paymentTypes.last.id%/edit   |
      | /payment-types/%paymentTypes.last.id%/delete |
