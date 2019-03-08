Feature: Superadmin can list all payment types
  In order to view all payment types
  As a superadmin
  I want to view the list of all payment types

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And I am logged as "superadmin@example.com"

  Scenario: I can add new payment types
    When I visit "/payment-types"
    Then I should see the "/payment-types/new" link

  Scenario: I view all payment types
    And there is a payment type:
      | name                 | days | endOfMonth |
      | 30 days end of month | 30   | false      |
    When I visit "/payment-types"
    Then I should see 1 "payment-type"

  Scenario: I view the payment types paginated
    Given there are payment types:
      | name | days | endOfMonth |
      | 10dd | 10   | false      |
      | 20dd | 20   | false      |
      | 30dd | 30   | false      |
      | 40dd | 40   | false      |
      | 50dd | 50   | false      |
      | 60dd | 60   | false      |
    When I am on "/payment-types"
    Then I should see 5 "payment-type"
    When I am on "/payment-types?page=2"
    Then I should see 1 "payment-type"
    When I am on "/payment-types?page=3"
    Then I should see 0 "payment-type"

  Scenario: I can handle payment types
    Given there are payment types:
      | name | days | endOfMonth |
      | 10dd | 10   | false      |
      | 20dd | 20   | false      |
    When I visit "/payment-types"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 1 link with class ".create"

