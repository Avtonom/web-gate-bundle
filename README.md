# web-gate-bundle
API for request and response to Rest and Soap 

=== install ===

kernel
            new Sensio\Bundle\BuzzBundle\SensioBuzzBundle(),
            new Avtonom\WebGateBundle\AvtonomWebGateBundle(),

params:

    web_gate.logging_level: 100
    web_gate.logging_max_files: 0
    web_gate.connection_timeout: 15


services:
    app.rest.client.get_user:
        class: Avtonom\WebGateBundle\Service\RestService
        arguments:
            - "@web_gate.logger"
            - "@buzz"
            - "GET"
            - "%request.api.host%"
            - "%request.api.env%/api/v1/user/"
            - "%request.api.login%"
            - "%request.api.password%"

Controller
$user = $this->get('app.rest.client.get_user')->send(['login' => 'test'], '/api/v1/user' . '/other_params');
