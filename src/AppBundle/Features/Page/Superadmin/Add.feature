Feature: Superadmin can add a page
  In order to add a new page
  As a superadmin
  I want to add a page filling a form

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"
    When I visit "/pages/new"

  Scenario: I can add a page
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a page
    Given I fill the "page" fields with:
      | translations.en.title   | Page title    |
      | translations.en.url     | page          |
      | translations.en.content | Page content  |
      | translations.it.title   | Titolo pagina |
      | translations.it.url     | pagina        |
      | translations.it.content | Contenuto     |
    When I press "Save and close"
    Then I should be on "/pages"
    And I should see 1 "page"

  Scenario: I cannot add a page without mandatory fields
    When I press "Save and close"
    Then I should be on "/pages/new"
    And I should see the "en" translations for "page" form errors:
      | title   | Empty title   |
      | url     | Empty url     |
      | content | Empty content |
    And I should see the "it" translations for "page" form errors:
      | title   | Empty title   |
      | url     | Empty url     |
      | content | Empty content |
