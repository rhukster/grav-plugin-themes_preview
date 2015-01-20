# Grav Themes Preview Plugin

`Themes Preview` is a [Grav](http://github.com/getgrav/grav) plugin and add the preview panel for your themes. For working you need to have Grav version 0.9.14+.

# Installation

Installing the plugin can be done in one of two ways. Our GPM (_Grav Package Manager_) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

> *IMPORTANT:* You need working with subdomains, so follow the instructions in the Usage section.

## GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (_also called the **command line**_).  From the root of your Grav install type:

    bin/gpm install themes_preview

This will install the Themes Preview plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/themes_preview`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `themes_preview`. You can find these files either on [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/themes_preview

>> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav), the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) plugins, and a theme to be installed in order to operate.

# Usage

For working correctly you need to use the environment configuration, you can learn how to from the [Grav Learn site](http://learn.getgrav.org/advanced/environment-config).

In the plugin configuration file you have `master_domain` you can configure with the main domain (_in my example is **grav.dev**_) or you can leave empty and the plugin give the theme key name from the last subdomain (_in my example **machine.grav.dev** get **machine**_).

The best way to have 3 files on config folder and edit this keys:

###### config/site.yaml

You can edit the site title for seo or anything.

    title: Grav Themes Preview - Machine

###### config/streams.yaml

In this case you need to remap the assets/images/pages/accounts streams to the subdomain/environment.

    schemes:
        asset:
            type: ReadOnlyStream
            paths:
                - machine.grav.dev/assets

        image:
            type: ReadOnlyStream
            paths:
                - user://machine.grav.dev/images

        page:
            type: ReadOnlyStream
            paths:
                - user://machine.grav.dev/pages

        account:
            type: ReadOnlyStream
            paths:
            - user://machine.grav.dev/accounts

###### config/system.yaml

In the end you need to change the default theme with your.

    pages:
        theme: machine

#### Folder structure example

Now you can see in my example what you need to working.

    user
        accounts
        config
        data
        pages
        plugins
        themes

        machine.grav.dev
            accounts
            assets
            config
                site.yaml
                streams.yaml
                system.yaml
            images
            pages
                01.home
                    default.md

# Updating

As development for this plugin continues, new versions may become available that add additional features and functionality, improve compatibility with newer Grav releases, and generally provide a better user experience. Updating this plugin is easy, and can be done through Grav's GPM system, as well as manually.

## GPM Update (_Preferred_)

The simplest way to update this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm). You can do this with this by navigating to the root directory of your Grav install using your system's Terminal (_also called **command line**_) and typing the following:

    bin/gpm update themes_preview

This command will check your Grav install to see if your plugin is due for an update. If a newer release is found, you will be asked whether or not you wish to update. To continue, type `y` and hit **enter**. The plugin will automatically update and clear Grav's cache.

## Manual Update

Manually updating this plugin is pretty simple. Here is what you will need to do to get this done:

* Delete the `your/site/user/plugins/themes_preview` directory.
* Download the new version of the Simple Form plugin from either [GetGrav.org](http://getgrav.org/downloads/plugins#extras).
* Unzip the zip file in `your/site/user/plugins` and rename the resulting folder to `themes_preview`.
* Clear the Grav cache. The simplest way to do this is by going to the root Grav directory in terminal and typing `bin/grav clear-cache`.

> **Note:** Any changes you have made to any of the files listed under this directory will also be removed and replaced by the new set. Any files located elsewhere (_for example a YAML settings file placed in_ `user/config/plugins`) will remain intact.
