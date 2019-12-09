Feature: Newsletter Subscriber form
  - In order to send opt-in emails to customers with Smaily
  - As a store owner
  - I want to use stores' built-in newsletter form

Scenario: New email is submitted in newsletter form
  Given newsletter form has only email field
  When customer subscribes form with email
  Then an opt-in email should be sent to the customer
  And store name field should be added to sent data

Scenario: New email is submitted in newsletter form that has extra fields
  Given newsletter form has extra fields
  When customer subscribes form with email
  Then an opt-in email should be sent to the customer
  And store name field should be added to sent data
  But don't add any other extra fields

Scenario: Subscribed email is submitted in newsletter form
  Given email submitted is allready in subscribers list
  When customer subscribes form with email
  Then an opt-in email should not be sent to the customer

Scenario: Unsubscribed email is submitted in newsletter form
  Given email submitted is allready in unsubscribers list
  When customer subscribes form with email
  Then an opt-in email should be sent to the customer

Scenario: Subscription conformation is enabled in store
  Given store has subscriber conformation enabled
  When customer subscribes form with email
  Then an opt-in email should not be sent to the customer

Scenario: Subscriber accepts subscription with conformation link
  Given customer has received email about conformation
  When customer confirms subscription with email link
  Then an opt-in email should be sent to the customer
