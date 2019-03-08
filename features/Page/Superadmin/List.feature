Feature: Superadmin can list all pages
  In order to view all pages
  As a superadmin
  I want to view the list of all pages

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"

  Scenario: I can add new pages
    When I visit "/pages"
    Then I should see the "/pages/new" link

  Scenario: I view all pages
    Given there is a page:
      | title |
      | Page  |
    When I visit "/pages"
    Then I should see 1 "page"

  Scenario: I view the pages paginated
    Given there are pages:
      | title |
      | Page1 |
      | Page2 |
      | Page3 |
      | Page4 |
      | Page5 |
      | Page6 |
    When I am on "/pages"
    Then I should see 5 "page"
    When I am on "/pages?page=2"
    Then I should see 1 "page"
    When I am on "/pages?page=3"
    Then I should see 0 "page"

  Scenario: I can handle pages
    Given there are pages:
      | title |
      | Page1 |
      | Page2 |
    When I visit "/pages"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

