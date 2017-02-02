@fixture-business_customer_crud.yml
Feature: Managing business customer
  In order to check business customer crud
  As an Administrator
  I want to be able to business customer entity

  Scenario: Business customer create
    Given I login as administrator
    Then I go to Customers/ Business Customers
    And I press "Create Business Customer"
    And I fill form with:
      | Account         | Marge Simpson          |
      | Customer Name   | SimpsonCustomer        |
      | Phones          | [11-11-11, 22-22-22]   |
      | Emails          | [m1@ex.com, m2@ex.com] |
      | Country         | United States          |
      | Street          | Selma Ave              |
      | City            | Los Angeles            |
      | Zip/Postal Code | 90028                  |
      | State           | California             |
    When I save and close form
    Then I should see Business Customer in grid with:
      | Account         | Marge Simpson                |
      | Customer Name   | SimpsonCustomer              |
      | Channel         | Business Customers           |
# TODO: I think CRM-5308 needed for this asserts
#      | Emails          | [m1@ex.com, m2@ex.com] |
#      | Phones          | [11-11-11, 22-22-22]         |
    When I go to Customers/ Business Customers
    Then I should see SimpsonCustomer in grid with following data:
      | Account         | Marge Simpson          |
      | Customer Name   | SimpsonCustomer        |
      | Email           | m1@ex.com              |
      | Channel         | Business Customers     |

  Scenario: Edit business customer
    Given I click Edit Bruce Customer in grid
    And I fill form with:
      | Account         | Keanu Reeves           |
      | Customer Name   | Charlie Customer       |
      | Phones          | [11-11-11, 22-22-22]   |
      | Emails          | [edited@ex.com]        |
      | Country         | United States          |
      | Street          | Selma Ave              |
      | City            | Los Angeles            |
      | Zip/Postal Code | 90028                  |
      | State           | California             |
    When I save and close form
    And I go to Customers/ Business Customers
    Then I should see Charlie Customer in grid with following data:
      | Account         | Keanu Reeves         |
      | Customer Name   | Charlie Customer     |
      | Channel         | Business Customers   |
      | Email           | edited@ex.com        |

  Scenario: Deleting business customer
    Given I click Delete SimpsonCustomer in grid
    And I confirm deletion
    Then I should see "Item deleted" flash message
    And there is one record in grid
    When I click view Charlie Customer in grid
    And I press "Delete Business Customer"
    And I confirm deletion
    Then there is no records in grid
