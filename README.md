backbone_srs: Student Record System
===================================

Using Backbone, with RequireJS, for a modular app pulling data from an Object Oriented set of php scripts. Another teaching and learning project this one uses Backbone with requireJS for a modular approach. With a set of php scripts to return json data from a database.

To get the code working you should read and alter server/resources/setEnv.php and also server/config/config.xml.php the first of these is commented to tell you how to alter it, config.xml.php isn't commented but you should find it easy to alter.  Don't remove the comment markers by the way since my code removes them.  The php file acts as a 'wrapper' around the xml.  You can use an xml file but put it outside your web directory and alter setEnv.php to change CONFIGLOCATION.

Do note that when the application runs the first time config.xml.php is read it's then written out to a compiled 'freeze' in the config directory, therefore, should you need to change the dns connection string in config.xml.php, remember to delete the freeze file else that will be read in preference to the non-compiled version when the application is run.

There is sql script in 'server/db/' which you can import into your database, or there is a sqlite database which might be the easiest to use.  Note that file would need public read and write access to the db directory and to the .sqlite file itself for it to work.

