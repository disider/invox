Feature: Accountant accepts an invite
  In order to account a company
  As an accountant
  I want to accept the company invite

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | owner1@example.com     |
      | owner2@example.com     |
    And there are companies:
      | name  | owner              |
      | Acme1 | owner1@example.com |
      | Acme2 | owner1@example.com |
      | Bros  | owner2@example.com |
    And there is an accountant invite:
      | email                  | company | token    |
      | accountant@example.com | Acme1   | 12345678 |
    And I am logged as "accountant@example.com"

  Scenario: I see the invite
    When I visit "/"
    Then I should see the "/invites" link
