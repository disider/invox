Feature: User can edit a service
  In order to modify a service
  As a user
  I want to edit service details

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a tax rate:
      | name | amount |
      | 10%  | 10     |
    And there is a service:
      | name     | company |
      | Service1 | Acme    |
    And I am logged as "user@example.com"

  Scenario: I can view a service details
    When I visit "/services/%services.Service1.id%/edit"
    Then I should see the "service" fields:
      | name | Service1 |

  Scenario: I can update a service
    When I visit "/services/%services.Service1.id%/edit"
    Then I can press "Save"

  Scenario: I update a service
    When I visit "/services/%services.Service1.id%/edit"
    And I fill the "service" fields with:
      | name          | New service name       |
      | code          | NC1                    |
      | measureUnit   | Liters                 |
      | taxRate       | %taxRates.last.id%     |
      | unitPrice     | 10                     |
      | tags          | ServiceCategory1       |
      | description   | This is a nice service |
      | internalNotes | This will be secret    |
    And I press "Save"
    Then I should be on "/services/%services.NC1.id%/edit"
    And I should see "successfully updated"
    And I should see the "service" fields:
      | name          | New service name       |
      | code          | NC1                    |
      | measureUnit   | Liters                 |
      | taxRate       | %taxRates.last.id%     |
      | unitPrice     | 10.00                  |
      | tags          | ServiceCategory1       |
      | description   | This is a nice service |
      | internalNotes | This will be secret    |

  Scenario: I cannot update a service without mandatory fields
    When I visit "/services/%services.Service1.id%/edit"
    And I fill the "service" fields with:
      | name |  |
    And I press "Save and close"
    Then I should see the "service" form errors:
      | name | Empty name |

  Scenario: I cannot edit an undefined service
    When I try to visit "/services/0/edit"
    Then the response status code should be 404
