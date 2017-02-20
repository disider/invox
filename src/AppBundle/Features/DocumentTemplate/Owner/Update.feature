Feature: Owner can edit a document template
  In order to modify a document template
  As an owner
  I want to edit the document template details

  Background:
    Given there is a user:
      | email             | role  |
      | owner@example.com | owner |
    And there is a document template:
      | name |
      | T1   |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    And I am logged as "owner@example.com"

  Scenario: I can view a document template details
    When I visit "/companies/%companies.last.id%/document-templates/%documentTemplatesPerCompany.last.id%/edit"
    Then I should see the "document_template" fields:
      | name | T1 |

  Scenario: I can update a document template
    When I visit "/companies/%companies.last.id%/document-templates/%documentTemplatesPerCompany.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a document template
    When I visit "/companies/%companies.last.id%/document-templates/%documentTemplatesPerCompany.last.id%/edit"
    And I fill the "document_template" fields with:
      | name | New name |
    And I press "Save and close"
    Then I should be on "/companies/%companies.last.id%/document-templates"
    And I should see "New name"

  Scenario: I cannot edit an undefined document template
    When I try to visit "/companies/%companies.last.id%/document-templates/0/edit"
    Then the response status code should be 404
