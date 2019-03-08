Feature: Owner can remove his accountant
  In order to change my accountant
  As an owner
  I want to disconnect my accountant

  Background:
    Given there is a user:
      | email                  | role       |
      | user@example.com       | user       |
      | accountant@example.com | accountant |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "user@example.com"

  Scenario: I can disconnect an accountant
    Given there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    When I visit "/companies/%companies.Acme.id%/accountant"
    Then I should see the "/companies/%companies.Acme.id%/disconnect-accountant" link

  Scenario: I cannot disconnect an accountant if not connected
    Given there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    When I visit "/companies/%companies.Acme.id%/disconnect-accountant"

  Scenario: I cannot disconnect an accountant if the demo mode is enabled
    Given there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And the demo mode is enabled
    When I visit "/companies/%companies.Acme.id%/disconnect-accountant"
    Then I should see "This action is not allowed in the demo"

  Scenario: I disconnect an accountant
    Given there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    When I visit "/companies/%companies.Acme.id%/disconnect-accountant"
    And I should see the "/companies/%companies.Acme.id%/accountant" link
    And I should see no "/companies/%companies.Acme.id%/disconnect-accountant" link

  Scenario: I cannot invite as accountant in demo mode
    Given the demo mode is enabled
    When I visit "/companies/%companies.Acme.id%/accountant"
    And I fill the "invite.email" field with "accountant@example.com"
    And I press "Invite"
    Then I should see "This action is not allowed in the demo"