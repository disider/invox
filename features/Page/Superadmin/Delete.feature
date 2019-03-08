Feature: Superadmin can delete a page
  In order to delete a page
  As a superadmin
  I want to delete a page

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a page:
      | title |
      | Page  |
    And I am logged as "superadmin@example.com"

  Scenario: I delete a page
    When I visit "/pages/%pages.last.id%/delete"
    Then I should be on "/pages"
    And I should see 0 "page"
