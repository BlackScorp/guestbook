## clone the repo

~~~
cd /some/location
git clone git@github.com:BlackScorp/guestbook.git
~~~

## install dependencies

~~~
curl -sS https://getcomposer.org/installer | php
php composer.phar install
~~~

## run tests
~~~
php tools/phpunit tests
~~~