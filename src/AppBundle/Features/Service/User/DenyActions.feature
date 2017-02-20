Feature: User cannot access services pages if service module is disabled

  Background:
    Given there are users:
      | email             |
      | owner@example.com |
    And there is a company:
      | name | owner             | modules |
      | Acme | owner@example.com |         |
    And there is a service:
      | name     | company |
      | Service1 | Acme    |
    And I am logged as "owner@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                   |
      | /services                               |
      | /services/new                           |
      | /services/%services.Service1.id%/edit   |
      | /services/%services.Service1.id%/delete |
