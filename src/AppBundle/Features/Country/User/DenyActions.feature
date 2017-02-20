Feature: User cannot access country pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a country:
      | code |
      | IT   |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                 |
      | /countries                            |
      | /countries/new                        |
      | /countries/%countries.last.id%/edit   |
      | /countries/%countries.last.id%/delete |
