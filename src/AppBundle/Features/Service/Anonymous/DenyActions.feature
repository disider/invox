Feature: Anonymous cannot access service pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a service:
      | name     | company |
      | Service1 | Acme    |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                   |
      | /services                               |
      | /services/new                           |
      | /services/%services.Service1.id%/edit   |
      | /services/%services.Service1.id%/delete |
