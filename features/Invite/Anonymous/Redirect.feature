Feature: Accountant can accept an invite
  In order to account a company
  As an accountant
  I want to accept the company invite

  Background:
    Given there is a user:
      | email             | role  |
      | owner@example.com | owner |
    And there is a company:
      | name | owner             |
      | Acme | owner@example.com |

  Scenario: I am redirected to the login page
    Given there is a user:
      | email                  |
      | accountant@example.com |
    Given there is an accountant invite:
      | email                  | company |
      | accountant@example.com | Acme    |
    When I visit "/invites/%invites.last.token%/view"
    Then I should be on "/login"

  Scenario: I am redirected to the register page
    Given there is an accountant invite:
      | email                    | company |
      | unregistered@example.com | Acme    |

    When I visit "/invites/%invites.last.token%/view"
    Then I should be on "/register?email=unregistered@example.com"
    And I should see the "registration.email" field with "unregistered@example.com"
