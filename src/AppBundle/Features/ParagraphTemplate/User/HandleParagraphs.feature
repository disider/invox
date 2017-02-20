Feature: User handles paragraph template paragraphs
  In order to modify a paragraph template's paragraphs
  As a user
  I want to add, delete, edit and move paragraph template paragraphs

  Background:
    Given there are users:
      | email             | password |
      | user1@example.com | secret   |
      | user2@example.com | secret   |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    Given there are paragraph templates:
      | title | company | parent |
      | PH1   | Acme    |        |
      | PH1.1 |         | PH1    |
      | PH1.2 |         | PH1    |
    And I am logged as "user1@example.com"
    And I visit "/paragraph-templates/%paragraphTemplates.PH1.id%/edit"

  @javascript
  Scenario: I can add a paragraph
    When I click the 3nd "Add paragraph" link
    And I fill the "paragraphTemplate.children.2" fields with:
      | title | PH1.3 |
    And I press "Save"
    Then I should see 3 "paragraph"s
    And I should see the "paragraphTemplate.children.0" fields:
      | title | PH1.1 |
    And I should see the "paragraphTemplate.children.2" fields:
      | title | PH1.3 |

  @javascript
  Scenario: I can delete a paragraph
    When I click the 1nd "Delete paragraph" link
    And I press "Save"
    Then I should see 1 "paragraph"
    And I should see the "paragraphTemplate.children.0" fields:
      | title | PH1.2 |

  @javascript
  Scenario: I can add a paragraph after deleting a paragraph
    When I click the 2nd "Delete paragraph" link
    And I click the 2nd "Add paragraph" link
    And I press "Save"
    Then I should see 2 "paragraph"s

  @javascript
  Scenario: I can move a paragraph up
    When I click on "paragraphTemplate_children_1_move_up"
    And I press "Save"
    Then I should see the "paragraphTemplate.children.0" fields:
      | title | PH1.2 |
    And I should see the "paragraphTemplate.children.1" fields:
      | title | PH1.1 |

  @javascript
  Scenario: I can move a paragraph down
    When I click on "paragraphTemplate_children_0_move_down"
    And I press "Save"
    Then I should see the "paragraphTemplate.children.0" fields:
      | title | PH1.2 |
    And I should see the "paragraphTemplate.children.1" fields:
      | title | PH1.1 |
