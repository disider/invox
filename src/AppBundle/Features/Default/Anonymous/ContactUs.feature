Feature: Anonymous user can contact the support team

  Background:
    Given I am on "/contact-us"

  Scenario: I contact the support team
    When I fill the "contact_us" fields with:
      | email   | customer@example.com         |
      | subject | A problem                    |
      | body    | The description of a problem |
    And I press "Send"
    Then I should see "Thank you"
    And a "contact_us" email should be sent from "customer@example.com"

  Scenario: I cannot contact the support without filling all the required fields
    When I press "Send"
    Then I should see the "contact_us" form errors:
      | email   | Empty email   |
      | subject | Empty subject |
      | body    | Empty body    |

  Scenario: I cannot contact the support without entering a valid email
    When I fill the "contact_us" fields with:
      | email | not_valid |
    And I press "Send"
    Then I should see the "contact_us" form errors:
      | email | Invalid email |