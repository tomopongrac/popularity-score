<p>
<a href="https://travis-ci.org/tomopongrac/popularity-score"><img src="https://www.travis-ci.com/tomopongrac/popularity-score.svg?branch=main" alt="Build Status"></a></p>

# Application description

This is a system that calculates the popularity of a particular word. The default word system searches the provider's service and, based on the number of positive and negative results, calculates the popularity rating of the given word from 0-10 (the result will be rounded to two decimal places).

# Setting up a project on a local server

Clone repository and install framework

```
git clone https://github.com/tomopongrac/popularity-score.git
cd popularity-score
composer install
```

## Using local server

Add this line to `.env.local`

```
DATABASE_URL="mysql://root:@127.0.0.1:3306/popularity_score"
```

Now you can start local server

```
php -S 127.0.0.1:8888 -t public
```

## Using docker

Add this line to `.env.local`

```
DATABASE_URL="mysql://symfony:secret@symfony-mysql:3306/symfony"
```

Now you can build containers and start docker

```
docker-compose up -d --build
```

# Using the application

To se how to use app check documentation on this [url](http://127.0.0.1:8888/api/doc).

You can send request to this url

```
http://127.0.0.1:8888/score?term={some-word}&version=1
```
or for version 2 send this request

```
http://127.0.0.1:8888/score?term={some-word}&version=2
```

Response for version 1 is

```
{
    term: "php",
    score: 3.33
}
```

and for version 2 is

```
{
    data: {
        term: "php",
        score: 3.33
    }
}
```

# Creating a new provider

If you want to create new provider you must implement interface `App\Service\Provider` and then you must update `services.yaml` for binding new provider.

# Versioning

For versioning we use package jms/serializer. For more information about package check [documentation](http://jmsyst.com/libs/serializer).

# Tests

All tests are writen in PHPUnit.

If you want to run all test run this command

```
./bin/phpunit
```

To run test for checking if Github API contains properties for our app run this

```
composer run-tests-outside
```

Or to run all other test run

```
composer run-tests
```