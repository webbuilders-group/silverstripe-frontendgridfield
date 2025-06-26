Front-End GridField
=================
Wraps gridfield adding support for using it on the front-end of a site.

## Maintainer Contact
* Ed Chipman ([UndefinedOffset](https://github.com/UndefinedOffset))

## Requirements
* SilverStripe Framework ~6.0


## Installation
* Download the module from here https://github.com/webbuilders-group/silverstripe-frontendgridfield/archive/master.zip
* Extract the downloaded archive into your site root so that the destination folder is called frontendgridfield, opening the extracted folder should contain _config.php in the root along with other files/folders
* Run dev/build?flush=all to regenerate the manifest


## Usage
Instead of using the GridField class you need to use FrontEndGridField for use on the front-end, note it is not recommended to be used in the CMS. As well instead of using the GridFieldConfig extensions provided with SilverStripe use FrontEndGridFieldConfig_Base, FrontEndGridFieldConfig_RecordEditor, FrontEndGridFieldConfig_RecordViewer, or FrontEndGridFieldConfig_RelationEditor. If you are building your own GridField config ensure that you use FrontEndGridFieldDetailForm instead of GridFieldDetailForm.
