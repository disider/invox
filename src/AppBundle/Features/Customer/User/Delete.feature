Feature: User can delete a customer
  In order to delete a customer
  As an user
  I want to delete a customer

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

  Scenario: I delete a customer
    Given there is a customer:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
    When I visit "/customers/%customers.Customer1.id%/delete"
    Then I should be on "/customers"
    And I should see 0 "customer"

  Scenario: I cannot delete a customer I don't own
    Given there is a customer:
      | name      | email                 | company |
      | Customer2 | customer2@example.com | Bros    |
    When I try to visit "/customers/%customers.Customer2.id%/delete"
    Then the response status code should be 403
