Feature: Welcome new subscribers
  - As a store owner
  - I want to send welcome emails
  - So that I will connect with my new subscribers immediately

  Entities:
    Guest - A person who has created an order or signed up for a newsletter. Guest has not created
      unique customer account, but has a distinct email address in Magento store.
    Customer - A person who has created an account in store.
    Newsletter Subscriber - Person who exists in newsletter subscribers list in Magento. Newsletter
      subscriber can have subscribed and unsubscribed status.
    Need to Confirm - A setting in Magneto store that requires subscribers to confirm their
      subscription before they can receive newsletters.

  Background:
    Given store owner has created opt-in automation in Smaily

  Rule: Welcome email is sent to new subscribers
    Scenario: Guest subscribes
      Given Guest fills newsletter sign-up form
      When guest submits the form
      Then welcome email is sent

    Scenario: Guest subscribes with an email address belonging to Newsletter Subscriber
      Given Guest fills newsletter sign-up form
      And the same email address belongs to Newsletter Subscriber
      When Guest submits the form
      Then no welcome email is sent

    Scenario: Guest subscribes with an email address belonging to Customer
      Given Guest fills newsletter sign-up form
      And the same email address already belongs to a Customer
      When Guest submits the form
      Then an error message is shown
      And no welcome email is sent

    Scenario: Customer subscribes
      Given Customer fills newsletter sign-up form
      When Customer submits the form
      Then welcome email is sent

    Scenario: Customer subscribes with an email address belonging to another Customer
      Given Customer fills newsletter sign-up form
      And the same email address already belongs to another Customer
      When Customer submits the form
      Then an error message is shown
      And no welcome email is sent
  
    Scenario: Customer subscribes in Customers' settings page
      Given Customer has unsubscribed status
      When Customer enables newsletter subscription
      And Customer saves newsletter status page
      Then welcome email is sent

    Scenario: Subscribed Customer saves settings page without changing subscription status
      Given Customer has subscribed status
      When Customer saves newsletter status page
      Then no welcome email is sent

    Scenario: Unsubscribed Customer saves settings page without changing subscription status
      Given Customer has unsubscribed status
      When Customer saves newsletter status page
      Then no welcome email is sent
    
    Scenario: Customer creates account and signs up for newsletter
      Given Customer fills out create account form
      And Customer has checked newsletter checkbox
      When Customer submits the form
      Then welcome email is sent

    Scenario: Customer creates account and does not sign up for newsletter
      Given Customer fills out create account form
      And Customer does not check the newsletter checkbox
      When Customer submits the form
      Then no welcome email is sent

  Rule: When need Need to Confirm is enabled, welcome message will be delivered after conformation
    Scenario: Guest subscribes when Need to Confirm is enabled in store
      Given store has subscriber confirmation enabled
      And Guest fills newsletter sign-up form
      When Guest submits the form
      Then Magento confirmation email is sent
      But no welcome email is sent

    Scenario: Guest accepts subscription with confirmation link
      Given Guest has received email about subscription confirmation
      When Guest confirms subscription with email link
      Then welcome email is sent

    Scenario: Customer accepts subscription with confirmation link
      Given Customer has received email about subscription confirmation
      When Customer confirms subscription with email link
      Then welcome email is sent
