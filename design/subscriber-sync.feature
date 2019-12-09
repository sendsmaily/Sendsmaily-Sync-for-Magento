Feature: Newsletter Subscriber synchronization
  - As a store owner
  - In order to save time on my newsletter preparations
  - I want to update subscribers information automatically

# Admin changes.
Scenario: Admin changes subscriber to unsubscribed
  Given subscriber has subscribed status
  And subscriber has subscribed status in Smaily
  When admin changes subscription status to unsubscribed
  Then subscriber status is synced to unsubscribed

Scenario: Admin changes subscriber to subscribed
  Given subscriber has unsubscribed status
  And subscriber has unsubscribed status in Smaily
  When admin changes subscription status to subscribed
  Then subscriber status is synced to unsubscribed

# Smaily changes.
Scenario: Subscriber unsubscribes in Smaily
  Given subscriber has subscribed status in Smaily
  When subscriber unsubscribes
  Then subscriber status is synced to unsubscribed

Scenario: Subscriber subscribes in Smaily
  Given subscriber does not exists in Magento
  When subscriber subscribes in Smaily
  Then subscriber is not synced

# Customer changes.
Scenario: Customer changes subscriber to unsubscribed
  Given subscriber has subscribed status
  And subscriber has subscribed status in Smaily
  When customer changes subscription status to unsubscribed
  Then subscriber status is synced to unsubscribed

Scenario: Customer changes subscriber to subscribed
  Given subscriber has unsubscribed status
  And subscriber has unsubscribed status in Smaily
  When customer changes subscription status to subscribed
  Then subscriber status is synced to unsubscribed

# State scenarios.
Scenario: Subscriber exists in store with subscribed status
  Given subscriber exists in store with subscribed status
  And subscriber does not exist in Smaily
  When customer synchronization is ran
  Then subscriber is created in Smaily

Scenario: Subscriber exists in store with unsubscribed status
  Given subscriber exists in store with unsubscribed status
  And subscriber does not exist in Smaily
  When customer synchronization is ran
  Then unsubscribed subscriber is created in Smaily
