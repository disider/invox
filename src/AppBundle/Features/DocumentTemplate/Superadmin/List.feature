Feature: Superadmin can list all document templates
  In order to view all document templates
  As a superadmin
  I want to view the list of all document template

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"

  Scenario: I can add new document template
    When I visit "/document-templates"
    Then I should see the "/document-templates/new" link

  Scenario: I view all document template
    Given there is a document template:
      | name |
      | T1   |
    When I visit "/document-templates"
    Then I should see 1 "document-template"

  Scenario: I view the document template paginated
    Given there are document templates:
      | name |
      | T1   |
      | T2   |
      | T3   |
      | T4   |
      | T5   |
      | T6   |
    When I am on "/document-templates"
    Then I should see 5 "document-template"
    When I am on "/document-templates?page=2"
    Then I should see 1 "document-template"
    When I am on "/document-templates?page=3"
    Then I should see 0 "document-template"

  Scenario: I can handle document template
    Given there are document templates:
      | name |
      | T1   |
      | T2   |
    When I visit "/document-templates"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

