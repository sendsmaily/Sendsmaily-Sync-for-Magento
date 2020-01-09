Feature: Google reCAPTCHA for newsletter sign up form
  - As a store owner
  - I want to block fake Newsletter Subscriber sign up
  - So I can keep my Newsletter Subscribers list clean

  Entities:
    Newsletter Subscriber - Email address in Magento´s newsletter subscribers list. Newsletter
      subscriber can have subscribed and unsubscribed status.

  Background: Store owner has created Google reCAPTCHA site

  Scenario: Store owner enables Google reCAPTCHA
    Given store owner has selected enable status for Google reCAPTCHA
    And has filled site and secret key
    When store owner saves config
    Then site and secret key is saved
    And Google reCAPTCHA is enabled
  
  Scenario: Newsletter sign up form is submitted
    Given Google reCAPTCHA is enabled
    And the newsletter sign up form is filled with correct Email
    When form is submitted
    And reCAPTCHA validation succeeds
    Then further logic is executed

  Scenario: Newsletter sign up form is submitted by bot
    Given Google reCAPTCHA is enabled
    And the newsletter sign up form is filled with correct Email
    When form is submitted
    And reCAPTCHA validation fails
    Then further logic is stopped
