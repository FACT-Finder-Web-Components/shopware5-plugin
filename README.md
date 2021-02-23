# FACT-Finder® Web Components for Shopware 5

This document helps you to integrate the FACT-Finder® Web Components SDK into your Shopware5 Shop. In addition, it gives a
concise overview of its primary functions. The first chapter *Installation* walks you through the suggested installation
process. The second chapter *Settings* explains the customisation options in the Shopware5 backend. The
 final chapter *Exporting Feed* describes how to use provided console command to export the feed.


- [Requirements](#requirements)
- [Installation](#installation)
- [Activating the Module](#activating-the-module)
- [Settings](#settings)
    - [Test Connection Button](#test-connection-button)
    - [Using FACT-Finder® on category pages](#using-fact-finder-on-category-pages)
    - [Upload Settings](#upload-settings)
    - [Import Settings](#import-settings)
    - [Single Fields](#single-fields)
- [Exporting Feed](#exporting-feed)
- [Adding New Column To Feed](#adding-new-column-to-feed)
- [Contribute](#contribute)
- [License](#license)


## Requirements
- Shopware 5.5 or higher
- PHP version 7.1 or higher


## Installation

To install the module, open your terminal and run the command:

    composer require omikron/shopware5-factfinder

![Module Enabled](docs/assets/module-enabled.png "Module enabled")


## Activating the Module
Once the module is activated, you can find the configurations page under `Configuration->PluginManager`.
All sections will be covered in the following paragraphs.


## Settings
![Main Settings](docs/assets/main-settings.png "Main settings")

This section contains a module configuration, which is required in order for the module to work.
All fields are self-explained.
Configuration set here is used by both Web Components and during the server side communication with FACT-Finder® instance.
Credentials you will be given should be placed here.

* Server URL - FACT-Finder® instance url   
  **Note:** Server URL should contain a used protocol: (e.g. `https://`) and should end with an endpoint ( `fact-finder` )
* Channel - Channel you want to serve data from
* Username
* Password
  **Note:** Module supports FACT-Finder® NG only.
  
### Test Connection Button
By clicking the `Test Connection` button you can check if your credentials are correct.
This functionality uses form data, so there is no need to save first.
**Note:** This functionality uses main sub shop channel input value.

### Using FACT-Finder® on category pages
Module in order to preserve categories URLs and hence SEO get use of standard Shopware routing with the combination of FACT-Finder® availability to pass custom parameters to search request.
Once user lands on category page search event is emitted immediately (thanks to `search-immediate` communication parameter usage).

### Upload Options
Following settings are used for uploading already exported feed to a given FTP server.
**Note:** FTP server should be configured to listen on default port 21

* FTP host
**Note:** Server URL should contain a used protocol: (e.g. `https://`)
* FTP user
* FTP password

### Import options
You can configure the module to trigger FACT-Finder® import after feed is uploaded to the configured FTP server. 
* Import data after export? - Enable/disable import
* Import data types - allow selecting types of import which need to be done. Possible types are: Suggest, Search and Recommendation

### Single Fields
This field allows you to select existing free text fields to be exported in a separated columns.
An additional data transformations are performed on the data of one of these types:
* BOOLEAN
* DATETIME
* FLOAT

Free text fields of different type are exported in non changed form.
For the field with Entity type defined, it will export the Identifier of that entity.

## Exporting Feed
There are two console commands located in the module `bin` directory, available for use.
Simply run them using the installed PHP CLI.

    php [SHOPWARE_ROOT]/bin/console factfinder:export:articles

The command could be run with additional argument indicating the shop context id which you want to export from.
The id is an integer value.
    
    php [SHOPWARE_ROOT]/bin/console factfinder:export:articles 1

There are two additional options:
* `-u` uploads the feed to the configured FTP server after feed is exported.
* `-i` runs the FACT-Finder® import with previously uploaded feed
  **Note:** This option works only in a combination with `-u` 
  
## Adding New Column To Feed
The standard feed contains all data FACT-Finder® requires to work.
However, you may want to export additional information which is relevant for your project and not part of a default Shopware 5 installation.
This section shows you how to extend the feed with additional column.

Start with creating field provider - a class implementing `OmikronFactfinder\Components\Data\Article\Fields\FieldInterface` which will be used to export your data.

```php
interface FieldInterface
{
    public function getName(): string;

    public function getValue(Detail $detail): string;
}
```

All classes implementing this interface are tagged by the module and in result they will be added to the export field collection.
Function `getValue` receive the article detail currently being exported.

```php

class CustomColumn implements FieldInterface
{
     public function getName(): string
     {
        return 'New column name';
     }

    public function getValue(Detail $detail): string
    {
        //fetching and transforming data logic for given article detail  
    }
}
```

## Contribute
For more information, click [here](.github/CONTRIBUTING.md)

## License
FACT-Finder® Web Components License. For more information see the [LICENSE](LICENSE) file.
