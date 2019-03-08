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
    And I am logged as "accountant@example.com"

  Scenario: I view an invite I received
    Given there is an accountant invite:
      | email                  | company |
      | accountant@example.com | Acme    |
    When I visit "/invites/%invites.last.token%/view"
    Then I can press "Accept"
    And I can press "Refuse"

  Scenario: I cannot view an invite I didn't receive
    Given there is an accountant invite:
      | email               | company |
      | unknown@example.com | Acme    |
    When I try to visit "/invites/%invites.last.token%/view"
    Then the response status code should be 403
