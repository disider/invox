Feature: User can list all his paragraph templates
  In order to view his paragraph templates
  As a user
  I want to view the list of all my paragraph templates

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I can add new paragraph templates
    When I visit "/paragraph-templates"
    Then I should see the "/paragraph-templates/new" link

  Scenario: I view all my paragraph templates
    Given there is a paragraph template:
      | title                | company |
      | Paragraph Template 1 | Acme    |
    When I visit "/paragraph-templates"
    Then I should see 1 "paragraph-template"

  Scenario: I view the paragraph templates paginated
    Given there are paragraph templates:
      | title                | company |
      | Paragraph Template 1 | Acme    |
      | Paragraph Template 2 | Acme    |
      | Paragraph Template 3 | Acme    |
      | Paragraph Template 4 | Acme    |
      | Paragraph Template 5 | Acme    |
      | Paragraph Template 6 | Acme    |
    When I am on "/paragraph-templates"
    Then I should see 5 "paragraph-template"
    When I am on "/paragraph-templates?page=2"
    Then I should see 1 "paragraph-template"
    When I am on "/paragraph-templates?page=3"
    Then I should see 0 "paragraph-template"

  Scenario: I can handle paragraph templates
    Given there are paragraph templates:
      | title                | company |
      | Paragraph Template 1 | Acme    |
      | Paragraph Template 2 | Acme    |
    When I visit "/paragraph-templates"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

  Scenario: I view no paragraph templates I don't own
    Given there are paragraph templates:
      | title                | company |
      | Paragraph Template 1 | Bros    |
      | Paragraph Template 2 | Bros    |
    When I visit "/paragraph-templates"
    Then I should see 0 "paragraph-template"

  Scenario: I can filter a paragraph template by title
    Given there are paragraph templates:
      | title                      | description | company |
      | Paragraph Template 1       | 001         | Acme    |
      | Other Paragraph Template 2 | 002         | Acme    |
    When I visit "/paragraph-templates"
    When I fill the "paragraphTemplatesFilter.title" field with "Other Paragraph"
    And I press "Filter"
    Then I should be on "/paragraph-templates"
    And I should see 1 "paragraph-template"
    And I should see "Other Paragraph Template 2"
