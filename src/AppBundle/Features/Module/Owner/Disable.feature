Feature: Owner can disable a module
  In order to stop using a module
  As an owner
  I want to disable a module

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             | modules          |
      | Acme | user1@example.com |                  |
      | Bros | user2@example.com | petty-cash-notes |

  Scenario: I can disable a module
    Given I am logged as "user2@example.com"
    When I visit "/modules"
    Then I should see no "/modules/petty-cash-notes/enable" link
    And I should see the "/modules/petty-cash-notes/disable" link

  Scenario: I disable a module
    Given I am logged as "user2@example.com"
    When I visit "/modules/petty-cash-notes/disable"
    Then I should see the "/modules/petty-cash-notes/enable" link
    And I should see no "/modules/petty-cash-notes/disable" link

  Scenario: I cannot disable an already disabled module
    Given I am logged as "user1@example.com"
    When I visit "/modules/petty-cash-notes/disable"
    Then I should see "not enabled"
