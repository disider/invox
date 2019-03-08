Feature: User can delete a paragraph template

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

  Scenario: I can delete a paragraph template
    When I visit "/paragraph-templates/%paragraphTemplates.last.id%/delete"
    Then I should be on "/paragraph-templates"
    And I should see 0 "paragraph-template"

  Scenario: I cannot delete an undefined paragraph template
    When I try to visit "/paragraph-templates/0/delete"
    Then the response status code should be 404