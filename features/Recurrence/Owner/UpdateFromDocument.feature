Feature: Owner can update a recurrence indirectly

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
    And there is a payment type:
      | name                 | days | endOfMonth |
      | 30 days end of month | 30   | false      |
    And I am logged as "user1@example.com"
    Given there are recurrences:
      | content | customer | company | startAt | occurrences |
      | R1      | C1       | Acme    | now     | 1           |
      | R2      | C1       | Acme    | now     | 1           |
    And there is an invoice:
      | customer             | company | ref | recurrence |
      | customer@example.com | Acme    | I01 | R1         |

  Scenario: I change document recurrence
    When I visit "/documents/%documents.last.id%/edit"
    And I fill the "document" form with:
      | recurrence          |
      | %recurrences.R2.id% |
    And I press "Save"
    And I visit "recurrences"
    Then I should see the "recurrence" rows:
      | content | next-due-date          |
      | R1      | %date('d/m/y', 'now')% |
      | R2      | Finished               |
    When I visit "/documents/%documents.last.id%/edit"
    And I fill the "document" form with:
      | recurrence          |
      | %recurrences.R1.id% |
    And I press "Save"
    And I visit "recurrences"
    Then I should see the "recurrence" rows:
      | content | next-due-date          |
      | R1      | Finished               |
      | R2      | %date('d/m/y', 'now')% |

  Scenario: I remove document recurrence
    When I visit "/documents/%documents.last.id%/edit"
    And I fill the "document" form with:
      | recurrence |
      |            |
    And I press "Save"
    And I visit "recurrences"
    Then I should see the "recurrence" rows:
      | content | next-due-date          |
      | R1      | %date('d/m/y', 'now')% |
      | R2      | %date('d/m/y', 'now')% |

  Scenario: I delete the document
    When I visit "recurrences"
    Then I should see the "recurrence" rows:
      | content | next-due-date          |
      | R1      | Finished               |
      | R2      | %date('d/m/y', 'now')% |
    When I visit "/documents/%documents.last.id%/delete"
    And I visit "recurrences"
    Then I should see the "recurrence" rows:
      | content | next-due-date          |
      | R1      | %date('d/m/y', 'now')% |
      | R2      | %date('d/m/y', 'now')% |
