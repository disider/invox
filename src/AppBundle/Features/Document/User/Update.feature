Feature: User updates a document
  In order to modify a document
  As a user
  I want to edit document details

  Background:
    Given there are users:
      | email             | password |
      | user1@example.com | secret   |
      | user2@example.com | secret   |
    And there are document templates:
      | name |
      | T1   |
      | T2   |
    And there are companies:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And there are customers:
      | name      | email                 | company |
      | Customer1 | customer1@example.com | Acme    |
      | Customer2 | customer2@example.com | Bros    |
    And there are quotes:
      | user              | customer              | ref | year | company |
      | user1@example.com | customer1@example.com | D01 | 2014 | Acme    |
      | user2@example.com | customer2@example.com | D02 | 2014 | Bros    |
    And there are tax rates:
      | name | amount |
      | 10%  | 10     |
      | 20%  | 20     |
    And there are document rows:
      | document | title     | unitPrice | quantity | taxRate |
      | D01      | Product 1 | 100       | 1        | 10      |
      | D01      | Product 2 | 200       | 2        | 20      |
    And I am logged as "user1@example.com"

  Scenario: I can view a document details
    When I visit "/documents/%documents.D01.id%/edit"
    Then I should see the "document" fields:
      | customerName | Customer1 |
      | ref          | D01       |
      | year         | 2014      |
    And I should see the "document" details:
      | taxes-1000  | 10.00  |
      | taxes-2000  | 80.00  |
      | net-total   | 500.00 |
      | gross-total | 590.00 |
    And I can press "Save"
    And I can press "Save and close"

  Scenario: I update a document
    Given I visit "/documents/%documents.D01.id%/edit"
    When I fill the "document" form with:
      | customerName | ref | year |
      | Updated name | D02 | 2013 |
    And I press "Save"
    Then I should see the "document" fields:
      | customerName | Updated name |
      | ref          | D02          |
      | year         | 2013         |
    And I should see "successfully updated"

  Scenario: I cannot edit a document I don't own
    When I try to visit "/documents/%documents.D02.id%/edit"
    Then the response status code should be 403

  Scenario: I update a document's company details
    Given I visit "/documents/%documents.D01.id%/edit"
    When I fill the "document" form with:
      | companyName |
      | New company |
    And I press "Save"
    Then I should see the "document" fields:
      | companyName | New company |
    And I should see "successfully updated"

  Scenario: I update a document's template
    Given I visit "/documents/%documents.D01.id%/edit"
    When I select the "document[documentTemplate]" option "T2"
    And I press "Save"
    Then I should see the "document[documentTemplate]" option "T2" selected
    And I should see "successfully updated"

  Scenario: I update a document with discount and rounding
    Given I visit "/documents/%documents.D01.id%/edit"
    When I fill the "document" form with:
      | discount | rounding |
      | 20       | 5        |
    And I press "Save"
    And I should see the "document" details:
      | net-total   | 500.00 |
      | gross-total | 575.00 |
    And I should see "successfully updated"

  Scenario: I update a document with percent discount
    Given I visit "/documents/%documents.D01.id%/edit"
    When I fill the "document" form with:
      | discount | rounding |
      | 20       | 5        |
    And I check the "document.discountPercent" field
    And I press "Save"
    And I should see the "document" details:
      | net-total   | 500.00 |
      | gross-total | 477.00 |
    And I should see "successfully updated"
