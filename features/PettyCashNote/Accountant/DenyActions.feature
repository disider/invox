Feature: Accountant cannot access petty cash pages

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | owner1@example.com     |
      | owner2@example.com     |
    And there is a company:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there is a customer:
      | name     | email                | company |
      | Customer | customer@example.com | Bros    |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And there is an account:
      | name | company |
      | Bank | Acme    |
    And there is a petty cash note:
      | accountFrom | amount |
      | Bank        | 10     |
    And I am logged as "accountant@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                             |
      | /petty-cash-notes/new                             |
      | /petty-cash-notes/%pettyCashNotes.last.id%/edit   |
      | /petty-cash-notes/%pettyCashNotes.last.id%/delete |
