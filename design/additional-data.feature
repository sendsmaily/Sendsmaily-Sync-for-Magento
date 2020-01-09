Feature: Customer fields synchronization
  - As a store owner
  - I want to synchronize customers additional details to Smaily
  - So I can send personalized messages and target subset of my subscribers

  Entities:
    Newsletter Subscriber - Email address in Magento´s newsletter subscribers list. Newsletter
      subscriber can have subscribed and unsubscribed status.
    Customer - A person who has created an account in store.

  Background: Store owner has enabled Newsletter Subscribers synchronization

  Scenario: Store owner has not selected any fields
    Given no fields are selected
    When Newsletter Subscribers are synchronized
    Then Customer fields are not synchronized

  Scenario: Store owner has selected field(s)
    Given Store owner has selected <field>
    And Customer has <field> set as <value>
    When Newsletter Subscribers are synchronized
    Then Customer <field> is synchronized as <value>

    Examples:
      |       field         |       value                         |
      | Subscription Type   | Constant customer                   |
      | Customer Group      | Magento customer group name         |
      | Customer ID         | Magento customer ID number          |
      | Prefix              | Magento customer prefix             |
      | First Name          | Magento customer first name         |
      | Last Name           | Magento customer last name          |
      | Gender              | Constant Male or Female             |
      | Date of Birth       | Date with format 2001-01-03         |
      | Website             | Magento website name for a customer |
      | Store Name          | Magento store name for a customer   |

  Rule: All selected fields are initialized with empty value
    Scenario: Store owner has selected field(s) that does not have value set in Magento
      Given Store owner has selected <field>
      And Customer does not have value for the <field>
      When Newsletter Subscribers are synchronized
      Then Customer <field> is synchronized as <value>

      Examples:
        |       field         |       value                         |
        | Subscription Type   | Cosntant guest                      |
        | Customer Group      | Empty value - ''                    |
        | Customer ID         | Empty value - ''                    |
        | Prefix              | Empty value - ''                    |
        | First Name          | Empty value - ''                    |
        | Last Name           | Empty value - ''                    |
        | Gender              | Empty value - ''                    |
        | Date of Birth       | Empty value - ''                    |
        | Website             | Magento website name for a customer |
        | Store Name          | Magento store name for a customer   |
