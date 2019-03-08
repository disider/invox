Feature: User can view his dashboard
  In order to view my dashboard
  As a logged user
  I want to view all the charts

  Background:
    Given there are users:
      | email                  | role       |
      | clerk@example.com      | clerk      |
      | owner1@example.com     | owner      |
      | owner2@example.com     | owner      |
      | owner3@example.com     | owner      |
      | accountant@example.com | accountant |
      | superadmin@example.com | superadmin |
    And there is a company:
      | name  | owner              | manager           | modules                                                                  |
      | Acme1 | owner1@example.com | clerk@example.com | accounts, petty-cash-notes, products, services, warehouse, working-notes |
      | Acme2 | owner1@example.com |                   | accounts, petty-cash-notes, products, services, warehouse, working-notes |
      | Bros  | owner2@example.com |                   | accounts, petty-cash-notes, products, services, warehouse, working-notes |
      | Cargo | owner3@example.com |                   | accounts, petty-cash-notes, products, services, warehouse, working-notes |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme1   |
      | accountant@example.com | Bros    |
    And there are enabled document types:
      | company | types                                                      |
      | Acme1   | invoice, receipt, credit_note, delivery_note, quote, order |
      | Acme2   | invoice, receipt, credit_note, delivery_note, quote, order |
      | Bros    | invoice, receipt, credit_note, delivery_note, quote, order |
      | Cargo   |                                                            |

  Scenario: I view the anonymous menu
    When I visit "/"
    And I should see the "/login" link
    And I should see the "/register" link
    And I should see no "/cities" link
    And I should see no "/companies" link
    And I should see no "/companies/%companies.last.id/edit" link
    And I should see no "/countries" link
    And I should see no "/customers" link
    And I should see no "/quotes" link
    And I should see no "/invoices" link
    And I should see no "/receipts" link
    And I should see no "/orders" link
    And I should see no "/credit-notes" link
    And I should see no "/delivery-notes" link
    And I should see no "/invites" link
    And I should see no "/media" link
    And I should see no "/logs" link
    And I should see no "/modules" link
    And I should see no "/paragraph-templates" link
    And I should see no "/petty-cash-notes" link
    And I should see no "/products" link
    And I should see no "/pages" link
    And I should see no "/payment-types" link
    And I should see no "/profile" link
    And I should see no "/provinces" link
    And I should see no "/services" link
    And I should see no "/tax-rates" link
    And I should see no "/users" link
    And I should see no "/working-notes" link
    And I should see no "/zip-codes" link
    And I should see no "/recurrences" link

  Scenario Outline: I view my menu
    Given I am logged as "<user>"
    When I visit "/"
    And I should see the "/login" link: <login>
    And I should see the "/register" link: <register>
    And I should see the "/accounts" link: <accounts>
    And I should see the "/customers" link: <customers>
    And I should see the "/cities" link: <cities>
    And I should see the "/companies" link: <companies>
    And I should see the "/companies/new" link: <add-company>
    And I should see the "/countries" link: <countries>
    And I should see the "/customers" link: <customers>
    And I should see the "/quotes" link: <quotes>
    And I should see the "/orders" link: <orders>
    And I should see the "/invoices" link: <invoices>
    And I should see the "/receipts" link: <receipts>
    And I should see the "/credit-notes" link: <credit-notes>
    And I should see the "/delivery-notes" link: <delivery-notes>
    And I should see the "/document-templates" link: <document-templates>
    And I should see the "/invites" link: <invites>
    And I should see the "/media" link: <media>
    And I should see the "/logs" link: <logs>
    And I should see the "/modules" link: <modules>
    And I should see the "/paragraph-templates" link: <paragraph-templates>
    And I should see the "/petty-cash-notes" link: <petty-cash-notes>
    And I should see the "/products" link: <products>
    And I should see the "/pages" link: <pages>
    And I should see the "/payment-types" link: <payment-types>
    And I should see the "/profile" link: <profile>
    And I should see the "/provinces" link: <provinces>
    And I should see the "/services" link: <services>
    And I should see the "/tax-rates" link: <tax-rates>
    And I should see the "/users" link: <users>
    And I should see the "/working-notes" link: <working-notes>
    And I should see the "/zip-codes" link: <zip-codes>

    Examples:
      | user                   | login | register | accounts | cities | companies | add-company | countries | customers | quotes | invoices | receipts | orders | credit-notes | delivery-notes | document-templates | invites | media | logs | modules | pages | payment-types | paragraph-templates | petty-cash-notes | products | profile | provinces | services | tax-rates | users | working-notes | zip-codes |
      | clerk@example.com      | n     | n        | y        | n      | n         | n           | n         | y         | y      | y        | y        | y      | y            | y              | n                  | n       | y     | n    | n       | n     | n             | y                   | y                | y        | y       | n         | y        | n         | n     | y             | n         |
      | owner1@example.com     | n     | n        | n        | n      | y         | n           | n         | n         | n      | n        | n        | n      | n            | n              | n                  | n       | n     | n    | n       | n     | n             | n                   | n                | n        | y       | n         | n        | n         | n     | n             | n         |
      | owner2@example.com     | n     | n        | y        | n      | n         | n           | n         | y         | y      | y        | y        | y      | y            | y              | y                  | n       | y     | n    | y       | n     | n             | y                   | y                | y        | y       | n         | y        | n         | n     | y             | n         |
      | owner3@example.com     | n     | n        | y        | n      | n         | n           | n         | y         | n      | n        | n        | n      | n            | n              | y                  | n       | y     | n    | y       | n     | n             | y                   | y                | y        | y       | n         | y        | n         | n     | y             | n         |
      | accountant@example.com | n     | n        | n        | n      | y         | y           | n         | n         | n      | n        | n        | n      | n            | n              | n                  | n       | n     | n    | n       | n     | n             | n                   | n                | n        | y       | n         | n        | n         | n     | n             | n         |
      | superadmin@example.com | n     | n        | n        | y      | y         | n           | y         | n         | n      | n        | n        | n      | n            | n              | y                  | y       | n     | y    | n       | y     | y             | n                   | n                | n        | y       | y         | n        | y         | y     | n             | y         |

  Scenario Outline: I view my menu after selecting a company
    Given I am logged as "<user>"
    And I visit "/companies/%companies.Acme1.id%/select"
    When I visit "/"
    And I should see the "/accounts" link: <accounts>
    And I should see the "/companies" link: <companies>
    And I should see the "/customers" link: <customers>
    And I should see the "/quotes" link: <quotes>
    And I should see the "/invoices" link: <invoices>
    And I should see the "/orders" link: <orders>
    And I should see the "/credit-notes" link: <credit-notes>
    And I should see the "/receipts" link: <credit-notes>
    And I should see the "/media" link: <media>
    And I should see the "/modules" link: <modules>
    And I should see the "/paragraph-templates" link: <paragraph-templates>
    And I should see the "/petty-cash-notes" link: <petty-cash-notes>
    And I should see the "/products" link: <products>
    And I should see the "/services" link: <services>
    And I should see the "/working-notes" link: <working-notes>
    And I should see the "/companies/close-current" link
    And I should see the "/companies/%companies.Acme1.id%/edit" link: <edit>
    And I should see the "/companies/%companies.Acme1.id%/view" link: <view>
    And I should see the "/companies/%companies.Acme1.id%/document-templates" link: <templates>

    Examples:
      | user                   | accounts | companies | customers | edit | quotes | invoices | orders | credit-notes | media | modules | paragraph-templates | petty-cash-notes | products | services | templates | working-notes | view |
      | owner1@example.com     | y        | y         | y         | y    | y      | y        | y      | y            | y     | y       | y                   | y                | y        | y        | y         | y             | n    |
      | accountant@example.com | y        | y         | n         | n    | n      | y        | n      | y            | n     | n       | n                   | y                | n        | n        | n         | n             | y    |
      | superadmin@example.com | y        | y         | y         | y    | y      | y        | y      | y            | y     | y       | y                   | y                | y        | y        | y         | y             | n    |
