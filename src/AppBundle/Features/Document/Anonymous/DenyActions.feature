Feature: Anonymous cannot access document pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a customer:
      | name     | email             | company |
      | Customer | customer@acme.com | Acme    |
    And there is a quote:
      | user             | customer          | ref | year | company |
      | user@example.com | customer@acme.com | 001 | 2014 | Acme    |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                 |
      | /documents/new                        |
      | /documents/%documents.last.id%/edit   |
      | /documents/%documents.last.id%/delete |
