Feature: Owner can list all his document templates
  In order to view all my document templates
  As an owner
  I want to view the list of all document template

  Background:
    Given there is a user:
      | email             | role  |
      | owner@example.com | owner |
    And I am logged as "owner@example.com"

  Scenario: I cannot add new document template
    Given there is a document template:
      | name |
      | T1   |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    When I visit "/companies/%companies.last.id%/document-templates"
    Then I should see no "/document-templates/new" link

  Scenario: I view all my document templates
    Given there is a document template:
      | name |
      | T1   |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    When I visit "/companies/%companies.last.id%/document-templates"
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
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    When I am on "/companies/%companies.last.id%/document-templates"
    Then I should see 5 "document-template"
    When I am on "/companies/%companies.last.id%/document-templates?page=2"
    Then I should see 1 "document-template"
    When I am on "/companies/%companies.last.id%/document-templates?page=3"
    Then I should see 0 "document-template"

  Scenario: I can handle document template
    Given there are document templates:
      | name |
      | T1   |
      | T2   |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    When I visit "/companies/%companies.last.id%/document-templates"
    Then I should see 4 links with class ".edit"
    Then I should see 2 links with class ".preview"
    Then I should see 2 links with class ".restore"
    And I should see 0 links with class ".delete"
    And I should see 0 link with class ".create"

  Scenario: I cannot handle document templates for a company I did not select
    Given there is a document template:
      | name |
      | T1   |
    And there are companies:
      | name           | owner             |
      | Acme           | owner@example.com |
      | AnotherCompany | owner@example.com |
    And I visit "/companies/%companies.Acme.id%/select"
    When I visit "/companies/%companies.Acme.id%/document-templates"
    Then I should see 2 links with class ".edit"
    And I should see 1 "document-template" row
