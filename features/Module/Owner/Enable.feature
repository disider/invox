Feature: Owner can enable a module
  In order to use a module
  As an owner
  I want to enable a module

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             | modules          |
      | Acme | user1@example.com |                  |
      | Bros | user2@example.com | petty-cash-notes |

  Scenario: I can enable a module
    Given I am logged as "user1@example.com"
    When I visit "/modules"
    Then I should see the "/modules/accounts/enable" link
    And I should see no "/modules/accounts/disable" link
    And I should see the "/modules/petty-cash-notes/enable" link
    And I should see no "/modules/petty-cash-notes/disable" link
    And I should see the "/modules/products/enable" link
    And I should see no "/modules/products/disable" link
    And I should see the "/modules/services/enable" link
    And I should see no "/modules/services/disable" link
    And I should see the "/modules/warehouse/enable" link
    And I should see no "/modules/warehouse/disable" link
    And I should see no "/accounts" link
    And I should see no "/petty-cash-notes" link
    And I should see no "/products" link
    And I should see no "/services" link
    And I should see no "/warehouse" link

  Scenario: I enable a module
    Given I am logged as "user1@example.com"
    When I visit "/modules/petty-cash-notes/enable"
    Then I should see no "/modules/petty-cash-notes/enable" link
    And I should see the "/modules/petty-cash-notes/disable" link

  Scenario: I cannot enable an already enabled module
    Given I am logged as "user2@example.com"
    When I visit "/modules/petty-cash-notes/enable"
    Then I should see "already enabled"
