Feature: Anonymous cannot access payment type pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a payment type:
      | name                 | days | endOfMonth |
      | 30 days end of month | 30   | false      |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                        |
      | /payment-types                               |
      | /payment-types/new                           |
      | /payment-types/%paymentTypes.last.id%/edit   |
      | /payment-types/%paymentTypes.last.id%/delete |
