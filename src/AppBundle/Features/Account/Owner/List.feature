Feature: Owner can list all his accounts
  In order to view his accounts
  As an owner
  I want to view the list of all my accounts

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I can add new accounts
    When I visit "/accounts"
    Then I should see the "/accounts/new" link

  Scenario: I view all my accounts
    Given there is an account:
      | name | company | initialAmount | currentAmount |
      | Bank | Acme    | 100           | 90            |
    When I visit "/accounts"
    Then I should see 1 "account"
    And I should see no "accounts" details rows:
      | name    | Bank |
      | balance | 10   |

  Scenario: I view the accounts paginated
    Given there are accounts:
      | name      | company |
      | Account 1 | Acme    |
      | Account 2 | Acme    |
      | Account 3 | Acme    |
      | Account 4 | Acme    |
      | Account 5 | Acme    |
      | Account 6 | Acme    |
    When I am on "/accounts"
    Then I should see 5 "account"
    When I am on "/accounts?page=2"
    Then I should see 1 "account"
    When I am on "/accounts?page=3"
    Then I should see 0 "account"

  Scenario: I can handle accounts
    Given there are accounts:
      | name      | company |
      | Account 1 | Acme    |
      | Account 2 | Acme    |
    When I visit "/accounts"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

  Scenario: I view no accounts I don't own
    Given there are accounts:
      | name      | company |
      | Account 1 | Bros    |
      | Account 2 | Bros    |
    When I visit "/accounts"
    Then I should see 0 "account"
