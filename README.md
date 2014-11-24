HOWTO Install

1. git clone git@github.com:%YOUR_FORK%/HouseFinder.git
2. sudo chmod +a "www-data allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs app/storage
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs app/storage
or
sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX app/cache app/logs app/spool app/storage
sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs app/spool app/storage
3. http://getcomposer.org/download/
4. php composer.phar install