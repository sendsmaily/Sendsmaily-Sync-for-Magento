Feature: Subscriber status update trigger from frontend settings page
  - In order to send opt-in and opt-out emails to customers
  - As a store owner
  - I want to use Smaily opt-in/out trigger when customer changes subscription status in settings

Scenario: Customer changes status to subscribed
  Given customer has unsubscribed status
  When customer saves newsletter status page with newsletter enabled
  Then an opt-in email should be sent to the customer

Scenario: Customer changes status to unsubscribed
  Given customer has subscribed status
  When customer saves newsletter status page with newsletter disabled
  Then an opt-out email should be sent to the customer

Scenario: New customer creates account
  Given there is a new customer creating account in store
  And customer has checked to receive newsletters checkbox
  When customer creates new account
  Then an opt-in email should be sent to the customer

Scenario: Subscribed customer saves settings page without changing status
  Given customer has subscribed status
  When customer saves newsletter status page with newsletter enabled
  Then an opt-in email should not be sent to the customer