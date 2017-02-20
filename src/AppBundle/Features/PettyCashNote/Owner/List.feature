Feature: Owner lists his petty cash notes
  In order to access my petty cash notes details
  As a user
  I can list all my petty cash notes

  Background:
    Given there are users:
      | email              |
      | owner1@example.com |
      | owner2@example.com |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there are accounts:
      | name    | company |
      | Bank1   | Acme    |
      | Credit1 | Acme    |
      | Bank2   | Bros    |
    And I am logged as "owner1@example.com"

  Scenario: I view a list of my petty cash notes
    Given there are petty cash notes:
      | accountFrom | accountTo | amount |
      |             | Bank1     | 10.00  |
      | Bank1       |           | 20.00  |
      | Bank1       | Credit1   | 20.00  |
    When I visit "/petty-cash-notes"
    Then I should see 3 "petty-cash-note"
    And I should see 1 "outcome"
    And I should see 1 "income"
    And I should see 1 "transfer"

  Scenario: I view the petty cash notes paginated
    Given there are petty cash notes:
      | accountFrom | amount |
      | Bank1       | 10.00  |
      | Bank1       | 20.00  |
      | Bank1       | 30.00  |
      | Bank1       | 40.00  |
      | Bank1       | 50.00  |
      | Bank1       | 60.00  |
    When I am on "/petty-cash-notes"
    Then I should see 5 "petty-cash-note"
    When I am on "/petty-cash-notes?page=2"
    Then I should see 1 "petty-cash-note"
    When I am on "/petty-cash-notes?page=3"
    Then I should see 0 "petty-cash-note"

  Scenario: I can handle petty cash notes
    Given there are petty cash notes:
      | accountFrom | amount |
      | Bank1       | 10.00  |
      | Bank1       | 10.00  |
    When I visit "/petty-cash-notes"
    Then I should see 6 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

  Scenario: I see no petty cash notes for a company I did not select
    Given there is a company:
      | name           | owner              |
      | AnotherCompany | owner1@example.com |
    And there is an account:
      | name        | company        |
      | AnotherBank | AnotherCompany |
    And there are petty cash notes:
      | accountFrom | amount |
      | AnotherBank | 10.00  |
    And I visit "/companies/%companies.Acme.id%/select"
    When I visit "/petty-cash-notes"
    Then I should see 0 "petty-cash-note"s

  Scenario: I view no petty cash notes I don't own
    And there are petty cash notes:
      | accountFrom | amount |
      | Bank2       | 10.00  |
      | Bank2       | 10.00  |
    When I visit "/petty-cash-notes"
    Then I should see 0 "petty-cash-note"
