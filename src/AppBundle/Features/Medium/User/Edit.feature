Feature: User can edit a medium

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

  Scenario: I can edit a medium
    Given I visit "/media/%media.last.id%/edit"
    When I fill the "medium" fields with:
      | fileName | fileName0 |
      | fileUrl  | fileUrl1  |
    And I press "Save"
    Then I should be on "/media/%media.last.id%/edit"
    And I should see the "medium" fields:
      | fileName | fileName0 |
      | fileUrl  | fileUrl1  |
    And I should see "successfully updated"

  Scenario: I cannot edit an undefined medium
    When I try to visit "/media/0/edit"
    Then the response status code should be 404