Feature: User can browse medium

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I see the media list
    Given there are paragraph templates:
      | title  | company |
      | title0 | Acme    |
      | title1 | Acme    |
      | title2 | Bros    |
    When I visit "/paragraph-templates/browse"
    Then I should see 2 "paragraph-template"s
