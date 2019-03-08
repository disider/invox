Feature: Owner cannot access company pages

  Background:
    Given there is a user:
      | email              |
      | owner1@example.com |
      | owner2@example.com |
    And there is a company:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And I am logged as "owner1@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                 |
      | /companies                            |
      | /companies/new                        |
      | /companies/%companies.Bros.id%/edit   |
      | /companies/%companies.Bros.id%/delete |
