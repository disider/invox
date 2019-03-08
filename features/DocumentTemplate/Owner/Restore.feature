Feature: Owner can edit a document template
  In order to modify a document template
  As an owner
  I want to edit the document template details

  Background:
    Given there is a user:
      | email             | role  |
      | owner@example.com | owner |
    And there is a document template:
      | name | header | content | footer |
      | T1   | Header | Content | Footer |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    And there is a company document template:
      | name | template | company | header | content | footer |
      | CT1  | T1       | Acme    |        |         |        |
    And I am logged as "owner@example.com"

  Scenario: I restore the company document template
    When I visit "/companies/%companies.last.id%/document-templates/%documentTemplatesPerCompany.CT1.id%/restore"
    Then I should see the "document_template" fields:
      | name    | T1      |
      | header  | Header  |
      | content | Content |
      | footer  | Footer  |
