Feature: User lists his petty cash notes
  In order to access my petty cash notes details
  As a user
  I can list all my petty cash notes

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | owner1@example.com     |
      | owner2@example.com     |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And there are accounts:
      | name  | company |
      | Bank1 | Acme    |
      | Bank2 | Bros    |
    And I am logged as "accountant@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I view a list of the company petty cash notes
    Given there is a petty cash note:
      | accountFrom | accountTo | amount |
      | Bank1       |           | 20.00  |
      |             | Bank1     | 10.00  |
    When I visit "/petty-cash-notes"
    Then I should see 2 "petty-cash-note"s

  Scenario: I cannot handle petty cash notes
    Given there are petty cash notes:
      | accountFrom | amount |
      | Bank1       | 10.00  |
      | Bank1       | 10.00  |
    When I visit "/petty-cash-notes"
    Then I should see 0 links with class ".edit"
    And I should see 0 links with class ".delete"
    And I should see 4 link with class ".view"
    And I should see 0 link with class ".create"

  Scenario: I view no petty cash notes I don't own
    And there are petty cash notes:
      | accountFrom | amount |
      | Bank2       | 10.00  |
      | Bank2       | 10.00  |
    When I visit "/petty-cash-notes"
    Then I should see 0 "petty-cash-note"s
