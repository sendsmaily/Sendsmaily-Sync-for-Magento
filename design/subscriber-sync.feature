Feature: Newsletter Subscribers synchronization
  - As a store owner
  - I want to automatically keep subscribers subscription status up to date
  - So that I will save time on my newsletter preparations

  Entities:
    Admin - A person that has admin rights in Magento store.
    Customer - A person who has created an account in store.
    Newsletter Subscriber - Email address in Magento´s newsletter subscribers list. Newsletter
      subscriber can have subscribed and unsubscribed status.

  Rule: Newsletter Subscribers synchronization is enabled by Store owner
    Scenario: Store owner enables Newsletter Subscribers synchronization
      Given Store owner is on module´s settings page
      And has selected enabled status for Newsletter Subscribers synchronization
      When configuration is saved
      Then Newsletter Subscribers synchronization is enabled

  Rule: Newsletter Subscribers are synchronized to Smaily
    Scenario: Newsletter Subscriber does not exist in Smaily
      Given there is a Newsletter Subscriber
      And the same Newsletter Subscriber does not exist in Smaily
      When subscribers are synchronized
      Then the Newsletter Subscriber is created in Smaily

    Scenario: Newsletter Subscriber is subscribed in Smaily
      Given there is a Newsletter Subscriber
      And the same Newsletter Subscriber is subscribed in Smaily
      When subscribers are synchronized
      Then the Newsletter Subscriber status stays subscribed

    # This can only happen when Smaily list is updated with subscribers manually or they are
    # collected in some other place.
    Scenario: Smaily only Newsletter Subscribers are not synchronized to Magento
      Given Newsletter Subscriber does not exist in Magento
      And the same Newsletter Subscriber exist in Smaily
      When subscribers are synchronized
      Then Newsletter Subscriber is not created in Magento

  Rule: Unsubscribed Newsletter Subscriber is always prime
    Scenario: Unsubscribed Newsletter Subscriber does not exist in Smaily
      Given there is a Newsletter Subscriber with unsubscribed status
      And the same Newsletter Subscriber does not exist in Smaily
      When subscribers are synchronized
      Then the Newsletter Subscriber with unsubscribed status is created in Smaily

    Scenario: Unsubscribed Newsletter Subscriber is subscribed in Smaily
      Given there is a Newsletter Subscriber with unsubscribed status
      And the same Newsletter Subscriber is subscribed in Smaily
      When subscribers are synchronized
      Then the Newsletter Subscriber status is changed to unsubscribed

    Scenario: Newsletter Subscriber is unsubscribed in Smaily
      Given there is a Newsletter Subscriber
      And the same Newsletter Subscriber is unsubscribed in Smaily
      When subscribers are synchronized
      Then the Newsletter Subscriber status is changed to unsubscribed
