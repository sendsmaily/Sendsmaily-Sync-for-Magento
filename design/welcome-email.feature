Feature: Welcome new subscribers
  - As a store owner
  - In order to welcome new subscribers
  - I want to send welcome emails

Background:
  Given store owner has created opt-in automation in Smaily

# Subscription form scenarios.
Scenario: Guest subscribes
  Given guest fills newsletter sign-up form
  When guest submits the form
  Then welcome email is sent

Scenario: Guest subscribes with an email address belonging to newsletter subscriber
  Given guest fills newsletter sign-up form
  And email address has already been subscribed to newsletter
  When guest submits the form
  Then no welcome email is sent

Scenario: Guest subscribes with an email address belonging to customer
  Given guest fills newsletter sign-up form
  And email address already belongs to a customer
  When guest submits the form
  Then error message is shown

Scenario: Customer subscribes
  Given customer fills newsletter sign-up form
  When customer submits the form
  Then welcome email is sent

Scenario: Customer subscribes with an email address belonging to another customer
  Given guest fills newsletter sign-up form
  And email address already belongs to another customer
  When guest submits the form
  Then error message is shown

# Double opt-in scenarios.
Scenario: Guest subscribes when Need to Confirm is enabled in store
  Given store has subscriber confirmation enabled
  And guest fills newsletter sign-up form
  When guest submits the form
  Then Magento confirmation email is sent

Scenario: Guest accepts subscription with confirmation link
  Given guest has received email about subscription confirmation
  When guest confirms subscription with email link
  Then welcome email is sent

Scenario: Customer accepts subscription with confirmation link
  Given customer has received email about subscription confirmation
  When customer confirms subscription with email link
  Then welcome email is sent

# Settings page update scenarios.
Scenario: Customer changes subscription status from unsubscribed to subscribed in customer settings page
  Given customer has unsubscribed status
  When customer changes subscription status to subscribed
  And customer saves newsletter status page
  Then welcome email is sent

Scenario: Subscribed customer saves settings page without changing subscription status
  Given customer has subscribed status
  When customer saves newsletter status page
  Then no welcome email is sent

Scenario: Unsubscribed customer saves settings page without changing subscription status
  Given customer has unsubscribed status
  When customer saves newsletter status page
  Then no welcome email is sent

# Account creation scenarios.
Scenario: Customer creates account and does not sign up for newsletter
  Given customer fills out create account form
  But customer does not check the newsletter checkbox
  When customer submits the form
  Then no welcome email is sent

Scenario: Customer creates account and signs up for newsletter
  Given customer fills out create account form
  And customer has checked newsletter checkbox
  When customer submits the form
  Then welcome email is sent
