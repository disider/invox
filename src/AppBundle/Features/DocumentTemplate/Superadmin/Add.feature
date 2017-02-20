Feature: Superadmin can add a document template
  In order to add a new document template
  As a superadmin
  I want to add a document template filling a form

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"
    When I visit "/document-templates/new"

  Scenario: I can add a document template
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a document template
    Given I fill the "document_template" fields with:
      | name                       | T1        |
      | textColor                  | #fff      |
      | tableHeaderBackgroundColor | #fff      |
      | headingColor               | #fff      |
      | tableHeaderColor           | #fff      |
      | fontFamily                 | Helvetica |
      | header                     | header    |
      | content                    | content   |
      | footer                     | footer    |
    When I press "Save and close"
    Then I should be on "/document-templates"
    And I should see 1 "document-template"

  Scenario: I cannot add a document template without mandatory fields
    When I press "Save and close"
    Then I should be on "/document-templates/new"
    And I should see the "document_template" form errors:
      | name                       | Empty name                          |
      | textColor                  | Empty text color                    |
      | tableHeaderBackgroundColor | Empty table header background color |
      | headingColor               | Empty heading color                 |
      | tableHeaderColor           | Empty table header color            |
      | fontFamily                 | Empty font family                   |
      | header                     | Empty header                        |
      | content                    | Empty content                       |
      | footer                     | Empty footer                        |
