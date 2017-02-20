Feature: Owner can list all valid modules
  In order to enable/disable modules
  As an owner
  I want to view the list of all the available modules

  Background:
    Given there is a user:
      | email             |
      | owner@example.com |
    And there is a company:
      | name | owner             |
      | Bros | owner@example.com |
    And I am logged as "owner@example.com"

  Scenario: I view all modules
    When I visit "/modules"
    Then I should see 7 "module"s
