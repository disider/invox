Feature: User cannot access warehouse pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there are products:
      | name     | company | enabledInWarehouse |
      | Product1 | Acme    | false              |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 404
    Examples:
      | route                                  |
      | /products/%products.last.id%/warehouse |
