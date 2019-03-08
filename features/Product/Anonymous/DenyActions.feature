Feature: Anonymous cannot access product pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a product:
      | name     | company |
      | Product1 | Acme    |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                   |
      | /products                               |
      | /products/new                           |
      | /products/%products.Product1.id%/edit   |
      | /products/%products.Product1.id%/delete |
