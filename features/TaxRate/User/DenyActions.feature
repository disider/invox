Feature: User cannot access tax rate pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a tax rate:
      | amount |
      | 22     |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                |
      | /tax-rates                           |
      | /tax-rates/new                       |
      | /tax-rates/%taxRates.last.id%/edit   |
      | /tax-rates/%taxRates.last.id%/delete |
