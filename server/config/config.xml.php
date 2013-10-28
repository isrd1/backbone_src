<?php
/**
 * @package classes
 * Config Settings XML file
 */
/**
 * @package classes
 * Because this is a sensitive config file with passwords etc then 'hide' it in a php wrapper comment the getXMLfromPHPFile function extracts the xml component, searching for the second less than up to the last star solidus

<config>
   <rootPath>
	/pathToApplication
   </rootPath>
   <username>yourUserName</username>
   <password>yourPassword</password>
   <dbType>sqlite:</dbType>
   <dns>mysql:host=localhost;dbname=backbone_srs</dns>
</config>

if you're using sqlite uncomment this instead:
<config>
   <rootPath>
	/pathToApplication
   </rootPath>
   <username></username>
   <password>yourPassword</password>
   <dbType>:</dbType>
   <dns>sqlite:/pathToApplication/server/backbone_srs.sqlite</dns>
</config>

*/ ?>