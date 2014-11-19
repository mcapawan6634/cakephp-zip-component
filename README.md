Cakephp Zip Component
=====================

A component for creating and reading zip files for cakePHP 2.x
based on ZipComponent by Sean Callan ([http://bakery.cakephp.org/articles/SeanCallan/2007/07/18/zip-component](http://bakery.cakephp.org/articles/SeanCallan/2007/07/18/zip-component))


Installation
=====================
Clone the repository to your plugins folder:

    git clone git://github.com/DIDoS/cakephp-zip-component.git Plugin/Zip

Load the plugin in the bootstrap.php:

    CakePlugin::load('Zip');

Add the component to your controller:

    public $components = ['Zip.Zip'];