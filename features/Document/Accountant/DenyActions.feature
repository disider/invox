Feature: Accountant cannot access document pages

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | owner1@example.com     |
      | owner2@example.com     |
    And there is a company:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there is a customer:
      | name     | email                | company |
      | Customer | customer@example.com | Bros    |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And there is a quote:
      | user               | customer             | ref | year | company |
      | owner2@example.com | customer@example.com | 001 | 2014 | Acme    |
    And I am logged as "accountant@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                 |
      | /documents/new                        |
      | /documents/%documents.last.id%/edit   |
      | /documents/%documents.last.id%/delete |
