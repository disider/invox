Feature: Anonymous cannot access warehouse pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there are products:
      | name     | company | enabledInWarehouse |
      | Product1 | Acme    | true               |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                  |
      | /products/%products.last.id%/warehouse |
