Feature: User can edit a paragraph template

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a paragraph template:
      | title  | description  | company |
      | title0 | description1 | Acme    |
    And I am logged as "user@example.com"

  Scenario: I can edit a paragraph template
    Given I visit "/paragraph-templates/%paragraphTemplates.last.id%/edit"
    When I fill the "paragraphTemplate" fields with:
      | title       | title0       |
      | description | description1 |
    And I press "Save"
    Then I should be on "/paragraph-templates/%paragraphTemplates.last.id%/edit"
    And I should see the "paragraphTemplate" fields:
      | title       | title0       |
      | description | description1 |
    And I should see "successfully updated"

  Scenario: I cannot edit an undefined paragraph template
    When I try to visit "/paragraph-templates/0/edit"
    Then the response status code should be 404