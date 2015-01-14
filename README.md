# Sendsmaily Sync for Magento
A [Magento](http://magento.com/ "eCommerce Software & eCommerce Platform Solutions | Magento") extension that allows you to export newsletter subscribers from your Magento administration interface to [Sendsmaily](https://sendsmaily.com/ "Sendsmaily").

**NOTE!** One of the most noticeable shortcoming of the extension is that it allows only one-way synchronization, i.e. subscribers can **only** be exported from Magento to Sendsmaily. It will be addressed in the next major version, after Sendsmaily has added the capability for data export through the API.

### Installation
1. Make sure you have Magento 1.7, 1.8 or 1.9 installed.
2. Download the latest relase from GitHub. If for some reason the `master` version does not work use the latest *versioned* release from [releases](https://github.com/sendsmaily/Sendsmaily-Sync-for-Magento/releases) page.
2. Extract `app` directory from the archive to your Magento root directory.
3. Make sure `app/code/community/Sendsmaily` (and it's subdirectories) have the correct permissions (refer to [Magento Installation Guide](http://www.magentocommerce.com/knowledge-base/entry/ce18-and-ee113-installing#install-privs "Installing and Verifying Magento Community Edition (CE) and Enterprise Edition (EE)"))
4. Flush Magento Cache from administration interface.
5. Done, move to configuration section.

### Configuration
Extension configuration can be found from Magento administration interface, under `System` &rarr; `Configuration` &rarr; `Newsletter` &rarr; `Sendsmaily synchronization` section.

* **Enabled** &minus; Enabling the extension adds option `Sendsmaily` to `Export to:` field in `Newsletter` &rarr; `Newsletter subscribers` and allows execution of export CRON task. Defaults to "No".
* **Sendsmaily account** &minus; Subdomain part of the Sendsmaily account. For example `xxx` from `https://xxx.sendsmaily.net/`.
* **API key** &minus; Sendsmaily account's API key.
* **Additional data** &minus; Additional data related to customer. By default only email address and subscribed state is exported.
* **How often do you want the cron to run?** &minus; Export subscriber data on a regular basis. To disable regular exports leave the field empty. Defaults to once every day at 1:30 in the morning.

### License
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
