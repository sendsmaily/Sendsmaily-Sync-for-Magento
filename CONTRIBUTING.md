# Overview

This is helper documentation for setting up a development environment for developing the Smaily extension for Magneto 1. This development environment solution is using [docker-compose](https://docs.docker.com/compose/) for setting up Magento and MariaDB connection. Magneto container is based of [alexcheng1982 docker image](https://github.com/alexcheng1982/docker-magento).

## Setting up environment

This workflow uses [Tusk](https://rliebz.github.io/tusk/) - YAML-based task runner - to run tasks.
To see all available commands run:
```
tusk -h
```

## Building

To mirror local user to container you need to build the image first. Tusk file manages local user information. To mirror a different user you can modify default option values(`tusk build -h`).
```
tusk build
```

## Starting containers

Starting and stopping containers have a shortcut Tusk commands available so that you can start and stop containers from this folder.

To start the containers run:
```
tusk up
```
And to stop the containers run:
```
tusk down
```

## Installing sample-data

**Installation of the sample-data must happen before you install Magento**

This Magento image has a built-in script to install sample data. To install sample-data run:

```
tusk install-sampledata
```

## Installing Magento

This Magento image has a built-in script for Magento installation. To install Magento run:
```
tusk install-magento
```

## Installing Smaily For Magento module

To keep all the module files in a single folder we use [Modman](https://github.com/colinmollenhour/modman). Modman creates symlinks so you don't have to mix your extension files throughout the core code directories.

To install Smaily For Magento extension run:
```
tusk install-smaily
```


### Symlinks

The Smaily installation script uses [Allow Symlinks](https://github.com/sreichel/magento-StackExchange_AllowSymlink) extension so that we can use Modman for linking our extension files to Magento directories. The [SUPEE-9767 V2 released July 12th, 2017](https://magento.stackexchange.com/questions/183443/supee-9767-v2-possible-problems-and-solved-issues) patch removes Allow Symlinks section from Magento Admin.

**Check that you have symlinks enabled**

```System > Configuration > Advanced > Developer > Template Settings > Allow Symlinks```

When you create new files you need to let also Modman know about where to create new links.

After creating a new file add the routing description to `modman` file.

You can update symlinks in container with tusk command:
```
tusk update-symlinks
```

### Sending mail via MailHog

The development environment has included MailHog as an SMTP server. This will enable the Magento store to send out emails. MailHog has a web interface, so you can view mail which has been sent. The web interface is available at <http://localhost:8025>.

**Set SMTP server to MailHog**

To make Magento use MailHog, you must go to the admin panel and change the mail sending host and port.
Go to ```System > Configuration > System > Mail Sending Settings``` and change `host` to `mailhog`, and `port` to `1025`.
