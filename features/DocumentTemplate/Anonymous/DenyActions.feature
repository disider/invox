Feature: Anonymous cannot access document template pages

  Background:
    Given there is a user:
      | email            | password |
      | user@example.com | secret   |
    And there is a document template:
      | name |
      | T1   |

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then I should be on "/login"
    Examples:
      | route                                                  |
      | /document-templates                                    |
      | /document-templates/new                                |
      | /document-templates/%documentTemplates.last.id%/edit   |
      | /document-templates/%documentTemplates.last.id%/delete |
