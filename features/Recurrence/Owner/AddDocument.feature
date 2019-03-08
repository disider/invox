Feature: User adds a document for a recurrence
  In order to link a document to a recurrence
  As a user
  I want to add a document for a recurrence

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there is a customer:
      | name | email                 | company | vatNumber   |
      | C1   | customer1@example.com | Acme    | 01234567890 |
      | C2   | customer2@example.com | Bros    | 11234567890 |
    And I am logged as "user1@example.com"

  Scenario: I add a document for a recurrence
    Given there is a recurrence:
      | content | customer | company |
      | R1      | C1       | Acme    |
    When I visit "/documents/new?recurrenceId=%recurrences.last.id%"
    Then I should see the "document" fields:
      | linkedCustomer | %customers.C1.id%     |
      | recurrence     | %recurrences.last.id% |

  Scenario: I cannot add a document for an undefined recurrence
    When I try to visit "/documents/new?recurrenceId=-1"
    Then the response status code should be 404

  Scenario: I cannot add a document for a recurrence I don't own
    Given there is a recurrence:
      | content | customer | company |
      | R1      | C2       | Bros    |
    When I try to visit "/documents/new?recurrenceId=%recurrences.last.id%"
    Then the response status code should be 404
