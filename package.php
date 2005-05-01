<?php
/**
 * Script to generate package.xml file
 *
 * Taken from PEAR::Log, thanks Jon ;)
 *
 * $Id$
 */
require_once 'PEAR/PackageFileManager.php';
require_once 'Console/Getopt.php';

$version = '0.16.0';

$notes = <<<EOT
- use MDB2_Schema as an optional dependency for the schema installer
- forgot to pass 'create' param to installSchema() in installPermSchema()
- cleaned up _readImpliedRights()
- fixed bug in readUserRights() that would lead to overwriting the proper right keys
- fixed typo (bug #4109)
- updated authTableCols in the database drivers
- made readConfig() public
- use LIVEUSER_STATUS_AUTHINITERROR and LIVEUSER_STATUS_PERMINITERROR
- improved handling of defaults in the installer
- improved DB seq support in the installer
- options can now be passed to the installer
- made auth fields dynamic (thx dan)
- added more infos into the old file names
- updated MDB2 dependency
- typo fix (bug #4173)
- turned installer methods into static methods
- generate auth and perm schema on the fly (removed pre generated ones from cvs)
- separated schema generation and schema writing in two methods in the installer
- updated Globals.php to specify the unique indexes as required values and removed
  'ids' array since this information is no longer used in the admin
- example5 was added back
- demo data for examples 4 and 5 was converted to MDB2_Schema format
- a script to install database based examples can be found under docs/examples/demodata.php. Type php demodata.php -h for usage.
EOT;

$description = <<<EOT
  LiveUser is a set of classes for dealing with user authentication
  and permission management. Basically, there are three main elements that
  make up this package:

  * The LiveUser class
  * The Auth containers
  * The Perm containers

  The LiveUser class takes care of the login process and can be configured
  to use a certain permission container and one or more different auth containers.
  That means, you can have your users' data scattered amongst many data containers
  and have the LiveUser class try each defined container until the user is found.
  For example, you can have all website users who can apply for a new account online
  on the webserver's local database. Also, you want to enable all your company's
  employees to login to the site without the need to create new accounts for all of
  them. To achieve that, a second container can be defined to be used by the LiveUser class.

  You can also define a permission container of your choice that will manage the rights for
  each user. Depending on the container, you can implement any kind of permission schemes
  for your application while having one consistent API.

  Using different permission and auth containers, it's easily possible to integrate
  newly written applications with older ones that have their own ways of storing permissions
  and user data. Just make a new container type and you're ready to go!

  Currently available are containers using:
  PEAR::DB, PEAR::MDB, PEAR::MDB2, PEAR::XML_Tree and PEAR::Auth.
EOT;

$package = new PEAR_PackageFileManager();

$result = $package->setOptions(array(
    'package'           => 'LiveUser',
    'summary'           => 'User authentication and permission management framework',
    'description'       => $description,
    'version'           => $version,
    'state'             => 'beta',
    'license'           => 'LGPL',
    'filelistgenerator' => 'cvs',
    'ignore'            => array('package.php', 'package.xml', 'TODO', 'DefineGenerator'),
    'notes'             => $notes,
    'changelogoldtonew' => false,
    'simpleoutput'      => true,
    'baseinstalldir'    => '/LiveUser',
    'packagedirectory'  => './',
    'installexceptions' => array(
        'LiveUser.php'            => '/',
    ),
    'installas'         => array(
        'sql/Auth_XML.xml'           => 'misc/Auth_XML.xml',
        'sql/Perm_XML.xml'           => 'misc/Perm_XML.xml',
        'sql/README'                 => 'misc/schema/README',
        'sql/install.php'            => 'misc/schema/install.php',
        'docs/examples/demodata.php' => 'misc/demodata.php'
    ),
    'exceptions'         => array(
        'lgpl.txt' => 'doc'
    ),
    'dir_roles'         => array('sql'               => 'data',
                                 'docs'              => 'doc',
                                 'scripts'           => 'script')
));

if (PEAR::isError($result)) {
    echo $result->getMessage();
}

$package->addMaintainer('mw21st',  'lead',      'Markus Wolff',      'mw21st@php.net');
$package->addMaintainer('arnaud',  'lead',      'Arnaud Limbourg',   'arnaud@php.net');
$package->addMaintainer('lsmith',  'lead',      'Lukas Kahwe Smith', 'smith@backendmedia.com');
$package->addMaintainer('krausbn', 'developer', 'Bjoern Kraus',      'krausbn@php.net');
$package->addMaintainer('dufuz',   'lead',      'Helgi �ormar',      'dufuz@php.net');

$package->addDependency('php',              '4.2.0',      'ge',  'php', false);
$package->addDependency('PEAR',             '1.3.3',      'ge',  'pkg', false);
$package->addDependency('Event_Dispatcher', false,        'has', 'pkg', false);
$package->addDependency('Log',              '1.7.0',      'ge',  'pkg', true);
$package->addDependency('DB',               '1.6.0',      'ge',  'pkg', true);
$package->addDependency('MDB',              '1.1.4',      'ge',  'pkg', true);
$package->addDependency('MDB2',             '2.0.0beta4', 'ge',  'pkg', true);
$package->addDependency('MDB2_Schema',      false,        'has', 'pkg', true);
$package->addDependency('XML_Tree',         false,        'has', 'pkg', true);
$package->addDependency('Crypt_RC4',        false,        'has', 'pkg', true);

if (isset($_GET['make']) || (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'make')) {
    $result = $package->writePackageFile();
} else {
    $result = $package->debugPackageFile();
}

if (PEAR::isError($result)) {
    echo $result->getMessage();
}
