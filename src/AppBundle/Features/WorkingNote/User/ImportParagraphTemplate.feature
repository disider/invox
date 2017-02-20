Feature: User imports paragraph template in working note
  In order to modify a working note's paragraphs
  As a user
  I want to import paragraph template

  Background:
    Given there are users:
      | email             | password |
      | user1@example.com | secret   |
      | user2@example.com | secret   |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are paragraph templates:
      | title    | company | parent |
      | PHBROSS1 | Bros    |        |
      | PH1      | Acme    |        |
      | PH1.1    |         | PH1    |
      | PH1.2    |         | PH1    |
      | PH2      | Acme    |        |
    And there is a working note:
      | title | company |
      | WN1   | Acme    |
    And I am logged as "user1@example.com"
    And I visit "/working-notes/%workingNotes.WN1.id%/edit"

  @javascript
  Scenario: I can import a paragraph
    Given I click on "Add paragraph from template"
    And I wait to see "#template-selector"
    When I click on "PH2"
    And I wait to see 1 "paragraph"
    Then I should see the "workingNote.paragraphs.0" fields:
      | title       | PH2             |
      | description | PH2 description |

  @javascript
  Scenario: I can import multiple paragraphs
    Given I click on "Add paragraph from template"
    And I wait to see "#template-selector"
    And I click on "PH1"
    And I wait to see 3 "paragraph"s
    Then I should see the "workingNote.paragraphs.0" fields:
      | title       | PH1             |
      | description | PH1 description |
    And I should see the "workingNote.paragraphs.0.children.0" fields:
      | title       | PH1.1             |
      | description | PH1.1 description |
    And I should see the "workingNote.paragraphs.0.children.1" fields:
      | title       | PH1.2             |
      | description | PH1.2 description |

  @javascript
  Scenario: I can import different paragraphs
    Given I click on "Add paragraph from template"
    And I wait to see "#template-selector"
    And I click on "PH2"
    And I wait to see 1 "paragraph"
    When I click the 2nd "Add paragraph from template" link
    And I wait to see "#template-selector"
    And I click on "PH1"
    And I wait to see 4 "paragraph"s
    Then I should see the "workingNote.paragraphs.0" fields:
      | title       | PH2             |
      | description | PH2 description |
    And I should see the "workingNote.paragraphs.1" fields:
      | title       | PH1             |
      | description | PH1 description |
    And I should see the "workingNote.paragraphs.1.children.0" fields:
      | title       | PH1.1             |
      | description | PH1.1 description |
    And I should see the "workingNote.paragraphs.1.children.1" fields:
      | title       | PH1.2             |
      | description | PH1.2 description |
