# Smaily for Magento 1.x

A [Magento](http://magento.com/ "eCommerce Software & eCommerce Platform Solutions | Magento") extension that allows you to export newsletter subscribers from your Magento administration interface to [Sendsmaily](https://sendsmaily.com/ "Sendsmaily").

## Installation

1. Make sure you have Magento 1.7, 1.8 or 1.9 installed.
2. Download the latest relase from GitHub. If for some reason the `master` version does not work use the latest _versioned_ release from [releases](https://github.com/sendsmaily/Sendsmaily-Sync-for-Magento/releases) page.
3. Extract `app` directory from the archive to your Magento root directory.
4. Make sure `app/code/community/Sendsmaily` (and it's subdirectories) have the correct permissions (refer to [Magento Installation Guide](http://www.magentocommerce.com/knowledge-base/entry/ce18-and-ee113-installing#install-privs "Installing and Verifying Magento Community Edition (CE) and Enterprise Edition (EE)"))
5. Flush Magento Cache from administration interface.
6. Done, move to configuration section.

## Configuration

Extension configuration can be found from Magento administration interface, under `System` &rarr; `Configuration` &rarr; `Newsletter` &rarr; `Smaily Email Marketing And Automation` section.

- **General settings** &minus; Enables the extension and holds credentials related information.
- **Customer Synchronization** &minus; Manages subscriber synchronization settings. Select fields to sync and sync frequency.
- **Subscriber welcome/goodbye emails** &minus; Collect subscribers directly to Smaily and send welcome/goodbye emails with this feature.

## Usage

1. Go to `System` &rarr; `Configuration` &rarr; `Newsletter` &rarr; `Smaily Email Marketing And Automation`.
2. Select `Enabled` &rarr; `Yes` status in General settings
3. Insert your Smaily API authentication information and press `Save Config` to get started
4. To enable customer synchronization select `Enabled` &rarr; `Yes` status in Customer synchronization settings
5. Select fields you would like to sync and frequency
6. To send welcome/goodbye emails to subscribers select `Enabled` &rarr; `Yes` status in Subscriber welcome/goodbye emails form section.
7. We recommend you to use CAPTCHA and you can enable built-in google reCAPTCHA for newsletter form by selecting `Enabled` &rarr; `Yes` status in Enable CAPTCHA field.

## RSS-feed

RSS-feed can be found from `store_url/sendsmaily/rss`. RSS-feed outputs 50 last edited products. **Only active and visible products are shown.**

## Manual export

At any time you can trigger a manual export from `Newsletter` &rarr; `Newsletter subscribers` by selecting `Sendsmaily` from `Export to:` dropdown and hitting that `Export` button.

To export specific subscriber(s), filter desired subscribers using the fields under the table header and click `Export`.
**NOTE! All filtered subscribers are exported. Selecting rows has no effect.**

## Troubleshooting

**Regular export fails to run**

Usually a good place to start would be to check Magento CRON's `Schedule Ahead for` value. We have found that value of **60** works the best, if you are running daily exports.
