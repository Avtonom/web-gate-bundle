# web-gate-bundle
API for request and response to Rest and Soap 

#### To Install

``` bash
php composer.phar require "avtonom/web-gate-bundle"

```

Add the bundle to app/AppKernel.php

``` php

$bundles(
    ...
            new Sensio\Bundle\BuzzBundle\SensioBuzzBundle(),
            new Avtonom\WebGateBundle\AvtonomWebGateBundle(),
    ...

```

Configuration options (parameters.yaml):

``` yaml

    web_gate.soap.environment: dev
    web_gate.soap.connection_timeout: 15
    
    web_gate.logger.logging_max_files: 0
    web_gate.logger.logging_level: 100 

```

Configuration services (services.yaml):

``` yaml

services:
    app.rest.client.get_user:
        class: Avtonom\WebGateBundle\Service\RestService
        arguments:
            - "@web_gate.logger"
            - "@buzz"
            - "GET"
            - "%web_gate.rest.host%"
            - "%web_gate.rest.env%/api/v1/user/"
            - "%web_gate.rest.login%"
            - "%web_gate.rest.password%"

```

Controller

``` php

$user = $this->get('app.rest.client.get_user')->send(['login' => 'test'], '/api/v1/user' . '/other_params');

```
