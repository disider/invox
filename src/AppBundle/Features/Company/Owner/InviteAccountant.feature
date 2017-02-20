Feature: Owner can invite an accountant
  In order to link an accountant to a company
  As an owner
  I want to invite my accountant by email

  Background:
    Given there is a user:
      | email                  | role       |
      | owner@example.com      | owner      |
      | accountant@example.com | accountant |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |
    And I am logged as "owner@example.com"

  Scenario: I can invite an accountant
    When I visit "/companies/%companies.Acme.id%/accountant"
    Then I should see the "invite" fields:
      | email |  |

  Scenario: I invite an accountant
    Given I visit "/companies/%companies.Acme.id%/accountant"
    When I fill the "invite.email" field with "accountant@example.com"
    And I press "Invite"
    Then I should see "successfully invited"
    And I should see 1 "invite"
    And I should see the "Remove invite" link
    And a "invite_accountant" email should be sent to "accountant@example.com"

  Scenario: I can view my accountant
    Given there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    Then I visit "/companies/%companies.Acme.id%/accountant"
    And I should see 1 "accountant"
    And I should see the "Disconnect accountant" link

  Scenario: I cannot invite myself as accountant
    Given I visit "/companies/%companies.Acme.id%/accountant"
    When I fill the "invite.email" field with "owner@example.com"
    And I press "Invite"
    Then I should see an "Cannot invite yourself" error

  Scenario: I cannot invite an accountant with a wrong email
    Given I visit "/companies/%companies.Acme.id%/accountant"
    When I fill the "invite.email" field with "wrong email"
    And I press "Invite"
    Then I should see an "Invalid email" error
