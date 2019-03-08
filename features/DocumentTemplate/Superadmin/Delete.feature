Feature: Superadmin can delete a document template
  In order to delete a document template
  As a superadmin
  I want to delete a document template

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a document template:
      | name |
      | T1   |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a document template
    When I visit "/document-templates/%documentTemplates.last.id%/delete"
    Then I should be on "/document-templates"
    And I should see 0 "document-template"
