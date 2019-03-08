Feature: Anonymous cannot access company pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                 |
      | /companies                            |
      | /companies/new                        |
      | /companies/%companies.Acme.id%/edit   |
      | /companies/%companies.Acme.id%/delete |
