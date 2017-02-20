Feature: Owner can delete an recurrence
  In order to delete an recurrence
  As an owner
  I want to delete an recurrence

  Background:
    Given there is a user:
      | email             | password |
      | user1@example.com | secret   |
      | user2@example.com | secret   |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name | email                 | company |
      | C1   | customer@example.com  | Acme    |
      | C2   | customer1@example.com | Bros    |
    And I am logged as "user1@example.com"

  Scenario: I delete a recurrence
    Given there is a recurrence:
      | content | customer | company |
      | R1      | C1       | Acme    |
    When I visit "/recurrences/%recurrences.last.id%/delete"
    Then I should be on "/recurrences"
    And I should see 0 "recurrence"

  Scenario: Deleting a recurrence does not delete related documents
    Given there is a recurrence:
      | content | customer | company |
      | R1      | C1       | Acme    |
    And there are invoices:
      | customer             | company | ref | recurrence |
      | customer@example.com | Acme    | I01 | R1         |
      | customer@example.com | Acme    | I02 | R1         |
    When I visit "/recurrences/%recurrences.last.id%/delete"
    And I visit "/invoices"
    Then I should see 2 "invoice"

  Scenario: I cannot delete a recurrence I don't own
    Given there is a recurrence:
      | content | customer | company |
      | R1      | C2       | Bros    |
    When I try to visit "/recurrences/%recurrences.last.id%/delete"
    Then the response status code should be 403

  Scenario: I cannot delete an undefined recurrence
    When I try to visit "/recurrences/0/delete"
    Then the response status code should be 404
