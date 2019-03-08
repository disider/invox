Feature: Anonuymous user can view error pages
  In order to see the error page
  As an anonymous user
  I want to see the error page details

  Scenario: I view the unauthorized page
    When I visit "/_error/403"
    Then I should see "Access denied"
    #And the response status code should be 403

  Scenario: I view the not found page
    When I visit "/_error/404"
    Then I should see "Not found"
    #And the response status code should be 404

  Scenario: I view the 500 error page
    When I visit "/_error/500"
    Then I should see "Internal server error"
    #And the response status code should be 500
