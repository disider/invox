Feature: User creates a medium

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "user@example.com"

  Scenario: I create a medium
    Given I visit "/media/new"
    When I fill the "medium" fields with:
      | fileName | fileName0 |
      | fileUrl  | test.png  |
    And there is a file "test.png" into the "medium" orphanage
    And I press "Save"
    Then I should be on "/media/%media.last.id%/edit"
    And a file should be saved into "/uploads/companies/%companies.last.id%/media/%media.last.fileUrl%"
