Feature: Accountant cannot access quote pages

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | owner1@example.com     |
    And there is a company:
      | name | owner              |
      | Acme | owner1@example.com |
    And there is a customer:
      | name     | email                | company |
      | Customer | customer@example.com | Acme    |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And there is a quote:
      | user               | customer             | ref | year | company |
      | owner1@example.com | customer@example.com | 001 | 2014 | Acme    |
    And I am logged as "accountant@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route         |
      | /quotes       |
      | /orders       |
