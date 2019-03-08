Feature: User cannot access document template pages

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a document template:
      | name |
      | T1   |
    And I am logged as "user@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                                  |
      | /document-templates                                    |
      | /document-templates/new                                |
      | /document-templates/%documentTemplates.last.id%/edit   |
      | /document-templates/%documentTemplates.last.id%/delete |
