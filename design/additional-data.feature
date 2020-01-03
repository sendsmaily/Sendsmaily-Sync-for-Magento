Feature: Additional fields synchronization
  - As a store owner
  - I want to synchronize subscribers´ additional details to Smaily
  - So I can send personalized messages and target subset of my subscribers

  Entities:
    Newsletter Subscriber - Email address in Magento´s newsletter subscribers list. Newsletter
      subscriber can have subscribed and unsubscribed status.
    Customer - A person who has created an account in store.

  Rule: Additional information moves from Magento to Smaily
    Scenario: Use a personal detail on newsletter
      Given there are these personal details in Magento:
      | Prefix | First Name | Last Name | Gender | Date of Birth |
      And Newsletter Subscriber has a personal detail set
      When Newsletter Subscriber information is added to Smaily
      Then I can use that detail in newsletter template

    Scenario: Use a personal detail on newsletter that does not exist in Magento
      Given there are these personal details in Magento:
      | Prefix | First Name | Last Name | Gender | Date of Birth |
      And Newsletter Subscriber does not have a personal detail set
      When Newsletter Subscriber information is added to Smaily
      Then this detail is unavailable in newsletter template

    Scenario: Use a Customer detail to filter subscribers
      Given there are Customer details in Magento:
      | Customer Group | Customer ID | Store | Subscription Type |
      And Customer has this detail set
      And this Customer is a Newsletter Subscriber
      When Customer information is added to Smaily
      Then I can use that detail to filter Newsletter Subscribers

  Rule: Additional information in Smaily is not added to Magento
    Scenario: There is extra information available in Smaily
      Given there is Newsletter Subscriber in Smaily
      And the same Newsletter Subscriber exists in Magento
      But in Smaily there is an extra information field set
      When Newsletter Subscribers information is added to Smaily
      Then that extra field is not imported to Magento
