===============================
 JV Amazon Advertising INSTALL
===============================
2009 Jerome Vieilledent


REQUIREMENTS
============

Currently, the only requirements are :

 * eZ Publish 4.x
 * cURL extension (compiled with PHP in most cases)


INSTALLATION
============

1. Unpack the package under *extension/* directory

2. Activate **jvamazonadvertising** through the backoffice or directly in *settings/override/site.ini.append.php* :
   ::
     [ExtensionSettings]
     ActiveExtensions[]=jvamazonadvertising
    
     If you run several sites using only one distribution and only some of the
     sites should use the extension, make the changes in the override file of
     that siteaccess.
     E.g root_of_ezpublish/settings/siteaccess/news/site.ini.append(.php)
     But instead of using ActiveExtensions you must add these lines instead:

     [ExtensionSettings]
     ActiveAccessExtensions[]=jvamazonadvertising

3. Regenerate the classes autoload array for extensions :
   ::
     $ php bin/php/ezpgenerateautoloads.php -e

4. Configure your Amazon Web Services account (AWS) in an override of **amazonadvertising.ini** (see comments in amazonadvertising.ini for details)

5. Clear the cache :
   ::
     $ php bin/php/ezcache.php --clear-tag=ini
     $ php bin/php/ezcache.php --clear-all 
