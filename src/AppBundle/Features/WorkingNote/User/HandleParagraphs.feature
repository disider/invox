Feature: User handles working note paragraphs
  In order to modify a working note's paragraphs
  As a user
  I want to add, delete, edit and move working note paragraphs

  Background:
    Given there are users:
      | email             | password |
      | user1@example.com | secret   |
      | user2@example.com | secret   |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    Given there is a working note:
      | title | company |
      | WN1   | Acme    |
    And there are working note paragraphs:
      | workingNote | title |
      | WN1         | PH1   |
      | WN1         | PH2   |
    And I am logged as "user1@example.com"
    And I visit "/working-notes/%workingNotes.WN1.id%/edit"

  @javascript
  Scenario: I can delete a paragraph
    When I click the 1nd "Delete paragraph" link
    And I press "Save"
    Then I should see 1 "paragraph"
    And I should see the "workingNote.paragraphs.0" fields:
      | title | PH2 |

  @javascript
  Scenario: I can add a paragraph after deleting a paragraph
    When I click the 2nd "Delete paragraph" link
    And I click the 2nd "Add paragraph" link
    And I press "Save"
    Then I should see 2 "paragraph"s

  @javascript
  Scenario: I can move a paragraph up
    When I click on "workingNote_paragraphs_1_move_up"
    And I press "Save"
    Then I should see the "workingNote.paragraphs.0" fields:
      | title | PH2 |
    And I should see the "workingNote.paragraphs.1" fields:
      | title | PH1 |

  @javascript
  Scenario: I can move a paragraph down
    When I click on "workingNote_paragraphs_0_move_down"
    And I press "Save"
    Then I should see the "workingNote.paragraphs.0" fields:
      | title | PH2 |
    And I should see the "workingNote.paragraphs.1" fields:
      | title | PH1 |

