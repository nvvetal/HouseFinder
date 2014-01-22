HOWTO Install

1. git clone git@github.com:%YOUR_FORK%/HouseFinder.git
2. sudo chmod +a "www-data allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs app/storage
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs app/storage
or
sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX app/cache app/logs app/spool app/storage
sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs app/spool app/storage
3. Install http://nodejs.org/ + npm for linux (https://npmjs.org/)
4. npm -g install less
5. http://getcomposer.org/download/
6. For linux edit composer.json:
"Mopa\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::postInstallSymlinkTwitterBootstrap"
Windows:
"Mopa\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::postInstallMirrorTwitterBootstrap"
7. php composer.phar install
8. Linux:
php app/console mopa:bootstrap:symlink:less
Windows:
php app/console mopa:bootstrap:symlink:less --no-symlink