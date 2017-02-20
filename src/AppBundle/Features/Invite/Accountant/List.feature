Feature: Accountant can list all his invites
  In order to accept or refuse invites
  As an accountant
  I want to view the list of all the invites I received

  Background:
    Given there is a user:
      | email                  | role       |
      | accountant@example.com | accountant |
      | owner1@example.com     | owner      |
      | owner2@example.com     | owner      |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there is an accountant invite:
      | email                  | company |
      | accountant@example.com | Acme    |
    And I am logged as "accountant@example.com"

  Scenario: I view all the invites I received
    When I visit "/invites"
    Then I should see 1 "invite"
    And I should see 1 link with class ".accept"
    And I should see 1 link with class ".refuse"
