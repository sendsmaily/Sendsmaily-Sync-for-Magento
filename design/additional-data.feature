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
      |       field         |       value       |
      | Subscription Type   |      customer     |
      | Customer Group      |      General      |
      | Customer ID         |        138        |
      | Prefix              |         Mr        |
      | First Name          |       John        |
      | Last Name           |        Doe        |
      | Gender              |   Male or Female  |
      | Date of Birth       |    2001-01-03     |
      | Website             |   Main Website    |
      | Store Name          |      English      |

  Rule: All selected fields are initialized with empty value
    Scenario: Store owner has selected field(s) that does not have value set in Magento
      Given Store owner has selected <field>
      And Customer does not have value for the <field>
      When Newsletter Subscribers are synchronized
      Then Customer <field> is synchronized as <value>

      Examples:
        |       field         |       value       |
        | Subscription Type   |       guest       |
        | Customer Group      |        ''         |
        | Customer ID         |        ''         |
        | Prefix              |        ''         |
        | First Name          |        ''         |
        | Last Name           |        ''         |
        | Gender              |        ''         |
        | Date of Birth       |        ''         |
        | Website             |    Main Website   |
        | Store Name          |     English       |
