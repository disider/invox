Feature: Superadmin can edit a page
  In order to modify a page
  As a superadmin
  I want to edit page details

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a page:
      | title      | content      |
      | Page title | Page content |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a page details
    When I visit "/pages/%pages.last.id%/edit"
    Then I should see the "page" fields:
      | translations.en.title   | Page title   |
      | translations.en.content | Page content |

  Scenario: I can update a page
    When I visit "/pages/%pages.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a page
    When I visit "/pages/%pages.last.id%/edit"
    And I fill the "page" fields with:
      | translations.en.title   | New title       |
      | translations.en.content | New content     |
      | translations.en.url     | new-url         |
      | translations.it.title   | Nuovo titolo    |
      | translations.it.content | Nuovo contenuto |
      | translations.it.url     | nuovo-url       |
    And I press "Save and close"
    Then I should be on "/pages"
    And I should see "New title"

  Scenario: I cannot edit an undefined page
    When I try to visit "/pages/0/edit"
    Then the response status code should be 404
