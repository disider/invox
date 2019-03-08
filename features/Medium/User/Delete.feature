Feature: User can delete a medium

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a medium:
      | fileName  | fileUrl  | company |
      | fileName0 | fileUrl1 | Acme    |
    And I am logged as "user@example.com"

  Scenario: I can delete a medium
    When I visit "/media/%media.last.id%/delete"
    Then I should be on "/media"
    And I should see 0 "medium"

  Scenario: I cannot delete an undefined medium
    When I try to visit "/media/0/delete"
    Then the response status code should be 404