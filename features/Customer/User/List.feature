Feature: User lists his customers
  In order to access my customer details
  As a user
  I can list all my customers

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

  Scenario: I view a list of my customers
    And there are customers:
      | name       | email                 | company |
      | Customer 1 | customer1@example.com | Acme    |
    When I visit "/customers"
    Then I should see 1 "customer"

  Scenario: I view the customers paginated
    Given there are customers:
      | name       | email                 | company |
      | Customer 1 | customer1@example.com | Acme    |
      | Customer 2 | customer2@example.com | Acme    |
      | Customer 3 | customer3@example.com | Acme    |
      | Customer 4 | customer4@example.com | Acme    |
      | Customer 5 | customer5@example.com | Acme    |
      | Customer 6 | customer6@example.com | Acme    |
    When I am on "/customers"
    Then I should see 5 "customer"
    When I am on "/customers?page=2"
    Then I should see 1 "customer"
    When I am on "/customers?page=3"
    Then I should see 0 "customer"

  Scenario: I can handle customers
    Given there are customers:
      | name       | email                 | company |
      | Customer 1 | customer1@example.com | Acme    |
      | Customer 2 | customer2@example.com | Acme    |
    When I visit "/customers"
    Then I should see 4 links with class ".edit"
    And I should see 2 links with class ".delete"
    And I should see 3 link with class ".create"

  Scenario: I view no customers I don't own
    Given there are customers:
      | name       | email                 | company |
      | Customer 1 | customer1@example.com | Bros    |
      | Customer 2 | customer2@example.com | Bros    |
    When I visit "/customers"
    Then I should see 0 "customer"

  Scenario: I view no customers for a company I did not select
    Given there are customers:
      | name       | email                 | company |
      | Customer 1 | customer1@example.com | Bros    |
      | Customer 2 | customer2@example.com | Bros    |
    When I visit "/customers"
    Then I should see 0 "customer"

  Scenario: I see no customers for a company I did not select
    And there is a company:
      | name           | owner             |
      | AnotherCompany | user1@example.com |
    Given there is a customer:
      | name     | email                | company        |
      | Customer | customer@example.com | AnotherCompany |
    And I visit "/companies/%companies.Acme.id%/select"
    When I visit "/customers"
    Then I should see 0 "customer"s

  Scenario: I can add a document for a customer
    Given there is a customer:
      | name       | email                 | company |
      | Customer 1 | customer1@example.com | Acme    |
    When I visit "/customers"
    Then I should see the "/documents/new?customerId=%customers.last.id%" link
