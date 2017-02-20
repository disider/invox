Feature: User cannot access company pages

  Background:
    Given there are users:
      | email             |
      | clerk@example.com |
      | owner@example.com |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    And I am logged as "clerk@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                 |
      | /companies                            |
      | /companies/%companies.Acme.id%/edit   |
      | /companies/%companies.Acme.id%/delete |
