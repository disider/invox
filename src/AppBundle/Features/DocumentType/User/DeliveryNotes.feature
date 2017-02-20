Feature: User lists his delivery notes
  In order to access my delivery note details
  As a user
  I can list all my delivery notes

  Background:
    Given there are users:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there is a quote:
      | ref | customer              | company |
      | Q01 | customer1@example.com | Acme    |
    And I am logged as "user1@example.com"

  Scenario: I can add new delivery notes
    When I visit "/delivery-notes"
    Then I should see the "/documents/new?type=delivery_note" link

  Scenario: I view a list of delivery notes
    Given there is a tax rate:
      | name | amount |
      | 0%   | 0      |
    And there are delivery notes:
      | ref | customer              | company |
      | D01 | customer1@example.com | Acme    |
      | D02 | customer1@example.com | Acme    |
    And there is a document row:
      | document | title     | unitPrice |
      | D01      | Product 1 | 100       |
    When I visit "/delivery-notes"
    Then I should see the "document" rows:
      | type          | ref             |
      | Delivery note | D01/%date('y')% |
      | Delivery note | D02/%date('y')% |

  Scenario: I view the delivery notes paginated
    Given there are delivery notes:
      | ref | customer              | year | company | direction |
      | 001 | customer1@example.com | 2014 | Acme    | incoming  |
      | 002 | customer1@example.com | 2014 | Acme    | incoming  |
      | 003 | customer1@example.com | 2014 | Acme    | incoming  |
      | 004 | customer1@example.com | 2014 | Acme    | incoming  |
      | 005 | customer1@example.com | 2014 | Acme    | incoming  |
      | 006 | customer1@example.com | 2014 | Acme    | incoming  |
    When I am on "/delivery-notes"
    Then I should see 5 "document"
    When I am on "/delivery-notes?page=2"
    Then I should see 1 "document"
    When I am on "/delivery-notes?page=3"
    Then I should see 0 "document"

  Scenario: I can handle delivery notes
    Given there are delivery notes:
      | customer              | ref | year | company |
      | customer1@example.com | 001 | 2014 | Acme    |
      | customer1@example.com | 002 | 2014 | Acme    |
    When I visit "/delivery-notes"
    Then I should see 2 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 2 links with class ".copy"
    And I should see 2 links with class ".view"
    And I should see 1 link with class ".create"

  Scenario: I view no delivery notes I don't own
    Given there is a delivery note:
      | customer              | ref | year | company |
      | customer2@example.com | 001 | 2014 | Bros    |
    When I visit "/delivery-notes"
    Then I should see 0 "document"

  Scenario: I view no delivery notes for a company I did not select
    Given there is a company:
      | name           | owner             |
      | AnotherCompany | user1@example.com |
    And there is a delivery note:
      | customer              | ref | year | company        |
      | customer1@example.com | 001 | 2014 | AnotherCompany |
    And I visit "/companies/%companies.Acme.id%/select"
    When I visit "/delivery-notes"
    Then I should see 0 "document"
