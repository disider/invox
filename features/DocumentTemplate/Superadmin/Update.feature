Feature: Superadmin can edit a document template
  In order to modify a document template
  As a superadmin
  I want to edit document template details

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a document template:
      | name |
      | T1   |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a document template details
    When I visit "/document-templates/%documentTemplates.last.id%/edit"
    Then I should see the "document_template" fields:
      | name | T1 |

  Scenario: I can update a document template
    When I visit "/document-templates/%documentTemplates.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a document template
    When I visit "/document-templates/%documentTemplates.last.id%/edit"
    And I fill the "document_template" fields with:
      | name | New name |
    And I press "Save and close"
    Then I should be on "/document-templates"
    And I should see "New name"

  Scenario: I cannot update a document template without mandatory fields
    When I visit "/document-templates/%documentTemplates.last.id%/edit"
    And I fill the "document_template" fields with:
      | name                       |  |
      | textColor                  |  |
      | tableHeaderBackgroundColor |  |
      | headingColor               |  |
      | tableHeaderColor           |  |
      | fontFamily                 |  |
      | header                     |  |
      | content                    |  |
      | footer                     |  |
    And I press "Save and close"
    Then I should see the "document_template" form errors:
      | name                       | Empty name                          |
      | textColor                  | Empty text color                    |
      | tableHeaderBackgroundColor | Empty table header background color |
      | headingColor               | Empty heading color                 |
      | tableHeaderColor           | Empty table header color            |
      | fontFamily                 | Empty font family                   |
      | header                     | Empty header                        |
      | content                    | Empty content                       |
      | footer                     | Empty footer                        |

  Scenario: I cannot edit an undefined document template
    When I try to visit "/document-templates/0/edit"
    Then the response status code should be 404
