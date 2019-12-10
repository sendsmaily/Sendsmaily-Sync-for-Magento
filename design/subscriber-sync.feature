Feature: Newsletter Subscriber synchronization
  - As a store owner
  - I want to update subscribers information automatically
  - So that I can save time on my newsletter preparations

  Entities:
    Admin - A person that has admin rights in Magento store.
    Visitor - A person that visits Magento store but is anonymous.
    Guest - A person who has created an order or signed up for a newsletter. Guest has not created
      unique customer account, but has a distinct email address in Magento store.
    Customer - A person who has created an account in store.
    Client - A person who is a guest or a customer.
    Subscriber - Person who has at one point joined newsletter list.
    Unsubscriber - Person who has canceled their subscription.

  Rule: Unsubscriber is always prime
    Scenario: Admin unsubscribes a subscriber in Magento
      Given there is a subscriber
      And the same subscriber exists in Smaily
      When admin unsubscribes a subscriber
      Then the subscriber is synced to unsubscriber

    Scenario: Admin subscribes a client in Magento
      Given there is a client
      And the same client is unsubscriber in Smaily
      When admin subscribes that client
      Then the client is synced to unsubscriber

    Scenario: Customer unsubscribes in Magento
      Given there is a subscriber
      And the same subscriber exists in Smaily
      When customer unsubscribes
      Then the customer is synced to unsubscriber

    Scenario: Customer subscribes in Magento
      Given there is an unsubscriber
      And the same unsubscriber exists in Smaily
      When customer subscribes
      Then the customer remains as unsubscriber

    Scenario: Subscriber unsubscribes
      Given there is a subscriber in Smaily
      And the same subscriber exists in Magento
      When subscriber unsubscribes
      Then the subscriber is synced to unsubscriber

    Scenario: Unsubscriber exists in Magento but not in Smaily
      Given there is an unsubscriber
      And the same unsubscriber does not exist in Smaily
      When customer synchronization is ran
      Then the unsubscriber is created in Smaily

  Rule: First time subscribers move to Smaily
    Scenario: New subscribers exists in Magento but not in Smaily
      Given there is a subscriber
      And the same subscriber does not exist in Smaily
      When customer synchronization is ran
      Then the subscriber is created in Smaily

  Rule: Smaily subscribers don't move to Magento
    Scenario: Subscriber subscribes
      Given subscriber does not exists in Magento
      When subscriber subscribes in Smaily
      Then subscriber is not added to Magento
