Feature: Anonymous cannot access tax rate pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a tax rate:
      | amount |
      | 22     |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                |
      | /tax-rates                           |
      | /tax-rates/new                       |
      | /tax-rates/%taxRates.last.id%/edit   |
      | /tax-rates/%taxRates.last.id%/delete |
