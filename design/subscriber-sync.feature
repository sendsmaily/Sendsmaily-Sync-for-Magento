Feature: Newsletter Subscriber synchronisation
  - In order to send updated customer statuses to Smaily
  - As a store owner
  - I want to use Smaily customer sync to send customer data to Smaily

Scenario: Fromer customer is subscribed
  Given fromer customer has subscribed status currently
  When customer synchronisation collects data
  Then it should collect data about that customer also
  But don't update subscribed status

Scenario: Fromer customer is unsubscribed
  Given fromer customer has unsubscribed status currently
  When customer synchronisation collects data
  Then it should collect data about that customer also
  And update subscription status to unsubscribed

Scenario: New customer creates account that is not signed up for newsletter
  Given customer has not confirmed subscription status
  And customer has not confirmed unsubscribed status
  When customer synchronisation collects data
  Then it should not collect information about that customer

Scenario: New customer creates account that is signed up for newsletter
  Given customer has confirmed subscription status
  When customer synchronisation collects data
  Then it should collect information about that customer
