Feature: Owner cannot access petty cash note pages if petty cash note module is disabled

  Background:
    Given there are users:
      | email             |
      | owner@example.com |
    And there is a company:
      | name | owner             | modules |
      | Acme | owner@example.com |         |
    And there is an account:
      | name  | company |
      | Bank1 | Acme    |
    And there is a petty cash note:
      | ref | accountFrom | amount |
      | PC1 | Bank1       | 10.00  |
    And I am logged as "owner@example.com"

  Scenario Outline: Denied actions
    When I am on "<route>"
    Then the response status code should be 403
    Examples:
      | route                                             |
      | /petty-cash-notes                                 |
      | /petty-cash-notes/new                             |
      | /petty-cash-notes/%pettyCashNotes.last.id%/edit   |
      | /petty-cash-notes/%pettyCashNotes.last.id%/delete |
      | /petty-cash-notes/%pettyCashNotes.last.id%/view   |
