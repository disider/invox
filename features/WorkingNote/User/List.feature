Feature: User can list all his working notes
  In order to view his working notes
  As a user
  I want to view the list of all my working notes

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

  Scenario: I can add new working notes
    When I visit "/working-notes"
    Then I should see the "/working-notes/new" link

  Scenario: I view all my working notes
    Given there is a working note:
      | title          | company |
      | Working Note 1 | Acme    |
    When I visit "/working-notes"
    Then I should see 1 "working-note"

  Scenario: I view the working notes paginated
    Given there are working notes:
      | title          | company |
      | Working Note 1 | Acme    |
      | Working Note 2 | Acme    |
      | Working Note 3 | Acme    |
      | Working Note 4 | Acme    |
      | Working Note 5 | Acme    |
      | Working Note 6 | Acme    |
    When I am on "/working-notes"
    Then I should see 5 "working-note"
    When I am on "/working-notes?page=2"
    Then I should see 1 "working-note"
    When I am on "/working-notes?page=3"
    Then I should see 0 "working-note"

  Scenario: I can handle working notes
    Given there are working notes:
      | title          | company |
      | Working Note 1 | Acme    |
      | Working Note 2 | Acme    |
    When I visit "/working-notes"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

  Scenario: I view no working notes I don't own
    Given there are working notes:
      | title          | company |
      | Working Note 1 | Bros    |
      | Working Note 2 | Bros    |
    When I visit "/working-notes"
    Then I should see 0 "working-note"

  Scenario: I can filter a working note by code
    Given there are working notes:
      | title                | code | company |
      | Working Note 1       | 001  | Acme    |
      | Other Working Note 2 | 002  | Acme    |
    When I visit "/working-notes"
    When I fill the "workingNotesFilter.code" field with "001"
    And I press "Filter"
    Then I should be on "/working-notes"
    And I should see 1 "working-note"
    And I should see "Working Note 1"

  Scenario: I can filter a working note by title
    Given there are working notes:
      | title                | code | company |
      | Working Note 1       | 001  | Acme    |
      | Other Working Note 2 | 002  | Acme    |
    When I visit "/working-notes"
    When I fill the "workingNotesFilter.title" field with "Other Working"
    And I press "Filter"
    Then I should be on "/working-notes"
    And I should see 1 "working-note"
    And I should see "Other Working Note 2"
