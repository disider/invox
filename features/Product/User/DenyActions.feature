Feature: User cannot access products pages if product module is disabled

  Background:
    Given there are users:
      | email             |
      | owner@example.com |
    And there is a company:
      | name | owner             | modules |
      | Acme | owner@example.com |         |
    And there is a product:
      | name     | company |
      | Product1 | Acme    |
    And I am logged as "owner@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                   |
      | /products                               |
      | /products/new                           |
      | /products/%products.Product1.id%/edit   |
      | /products/%products.Product1.id%/delete |
