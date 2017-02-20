Feature: Anonymous cannot access customer pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a customer:
      | name     | email                | company |
      | Customer | customer@example.com | Acme    |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                     |
      | /customers                                |
      | /customers/new                            |
      | /customers/%customers.Customer.id%/edit   |
      | /customers/%customers.Customer.id%/delete |
