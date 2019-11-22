# Smaily for Magento 1.x
A [Magento](http://magento.com/ "eCommerce Software & eCommerce Platform Solutions | Magento") extension that allows you to export newsletter subscribers from your Magento administration interface to [Sendsmaily](https://sendsmaily.com/ "Sendsmaily").

## Installation
1. Make sure you have Magento 1.7, 1.8 or 1.9 installed.
2. Download the latest relase from GitHub. If for some reason the `master` version does not work use the latest *versioned* release from [releases](https://github.com/sendsmaily/Sendsmaily-Sync-for-Magento/releases) page.
2. Extract `app` directory from the archive to your Magento root directory.
3. Make sure `app/code/community/Sendsmaily` (and it's subdirectories) have the correct permissions (refer to [Magento Installation Guide](http://www.magentocommerce.com/knowledge-base/entry/ce18-and-ee113-installing#install-privs "Installing and Verifying Magento Community Edition (CE) and Enterprise Edition (EE)"))
4. Flush Magento Cache from administration interface.
5. Done, move to configuration section.

## Configuration
Extension configuration can be found from Magento administration interface, under `System` &rarr; `Configuration` &rarr; `Newsletter` &rarr; `Smaily Email Marketing And Automation` section.

* **General settings** &minus; Enables the extension and holds credentials related information.
* **Customer Synchronization** &minus; Manages subscriber synchronization settings. Select fields to sync and sync frequency.
* **Newsletter form** &minus; Collect subscribers directly to Smaily from newsletter subscriber form. You can also send welcome emails with this feature.


## Usage

1. Go to `System` &rarr; `Configuration` &rarr; `Newsletter` &rarr; `Smaily Email Marketing And Automation`.
2. Select `Enabled` &rarr; `Yes` status in General settings
3. Insert your Smaily API authentication information and press `Save Config` to get started
4. To enable customer synchronization select `Enabled` &rarr; `Yes` status in Customer synchronization settings
5. Select fields you would like to sync and frequency
6. To collect subscribers directly to Smaily from newsletter form select `Enabled` &rarr; `Yes` status in Newsletter form section.
7. We recommend you to use CAPTCHA and you can enable built-in google reCAPTCHA by selecting `Enabled` &rarr; `Yes` status in Enable CAPTCHA field.

## RSS-feed

RSS-feed can be found from `store_url/sendsmaily/rss`. RSS-feed outputs 50 last edited products. **Only active and visible products are shown.**

## Manual export
At any time you can trigger a manual export from `Newsletter` &rarr; `Newsletter subscribers` by selecting `Sendsmaily` from `Export to:` dropdown and hitting that `Export` button.

To export specific subscriber(s), filter desired subscribers using the fields under the table header and click `Export`. 
**NOTE! All filtered subscribers are exported. Selecting rows has no effect.**

## Troubleshooting
**Regular export fails to run**

Usually a good place to start would be to check Magento CRON's `Schedule Ahead for` value. We have found that value of **60** works the best, if you are running daily exports.

## Changelog

### 2.0.2

- Opt-in emails trigger for newsletter from subscribers
- Google reCAPTCHA support for newsletter form

### 2.0.1

- Add support for direct products import to the Smaily RSS-feed.

### 2.0.0

- Refractor functionality to implement new API
- Customer Synchronization now removes unsubscribers from Magento store
- Store unsubscribers are also updated in Smaily system
- Use subdomain and username/password combination for authentication with Smaily
- Updated admin page look

## License
```
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
```
