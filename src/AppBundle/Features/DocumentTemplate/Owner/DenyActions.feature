Feature: Owner cannot access document template pages

  Background:
    Given there is a user:
      | email             |
      | owner@example.com |
    And there is a document template:
      | name |
      | T1   |
    And there is a company:
      | name | owner             | template |
      | Acme | owner@example.com | T1       |
    And I am logged as "owner@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                                  |
      | /document-templates/new                                |
      | /document-templates/%documentTemplates.last.id%/delete |
