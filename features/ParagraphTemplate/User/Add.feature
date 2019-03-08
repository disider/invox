Feature: User creates a paragraph template

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "user@example.com"

  Scenario: I create a paragraph template
    Given I visit "/paragraph-templates/new"
    When I fill the "paragraphTemplate" fields with:
      | title       | title0       |
      | description | description1 |
    And I press "Save"
    Then I should be on "/paragraph-templates/%paragraphTemplates.last.id%/edit"
