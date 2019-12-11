Feature: Newsletter Subscribers synchronization
  - As a store owner
  - I want to update subscribers automatically
  - So that I will save time on my newsletter preparations

  Entities:
    Admin - A person that has admin rights in Magento store.
    Customer - A person who has created an account in store.
    Newsletter Subscriber - Person who exists in newsletter subscribers list in Magento. Newsletter
      subscriber can have subscribed and unsubscribed status.

  Rule: New subscribers move to Smaily
    Scenario: Newsletter Subscriber does not exist in Smaily
      Given there is a Newsletter Subscriber
      And the same Newsletter Subscriber does not exist in Smaily
      When subscribers are updated
      Then the Newsletter Subscriber is created in Smaily

  Rule: Unsubscriber is always prime
    Scenario: Unsubscribed Newsletter Subscriber does not exist in Smaily
      Given there is an unsubscribed Newsletter Subscriber
      And the same unsubscriber does not exist in Smaily
      When subscribers are updated
      Then the unsubscriber is created in Smaily

    Scenario: Admin unsubscribes a Newsletter Subscriber
      Given there is a Newsletter Subscriber
      And the same Newsletter Subscriber is subscribed in Smaily
      When admin unsubscribes Newsletter Subscriber
      Then the Newsletter Subscriber is unsubscribed

    Scenario: Admin subscribes a Newsletter Subscriber
      Given there is a Newsletter Subscriber
      And the same Newsletter Subscriber is unsubscribed in Smaily
      When admin subscribes that Newsletter Subscriber
      Then the Newsletter Subscriber stays unsubscribed

    Scenario: Customer unsubscribes in Magento
      Given there is a subscribed Customer
      And the same subscriber exists in Smaily
      When Customer unsubscribes
      Then the Customer is unsubscribed

    Scenario: Customer subscribes in Magento
      Given there is an unsubscribed Customer
      And the same unsubscriber exists in Smaily
      When Customer subscribes
      Then the Customer stays unsubscribed

    Scenario: Subscriber unsubscribes
      Given there is a subscriber in Smaily
      And the same Newsletter Subscriber exists in Magento
      When subscriber unsubscribes
      Then the Newsletter Subscriber is unsubscribed

  Rule: Smaily subscribers do not move to Magento
    # This can only happen when Smaily list is updated with subscribers manually or they are
    # collected in some other place.
    Scenario: Subscriber did not come from Magento store
      Given Newsletter Subscriber does not exist in Magento
      When subscriber subscribes in Smaily
      Then subscriber is not added to Magento
