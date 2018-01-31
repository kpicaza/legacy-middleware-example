<?php

namespace App;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class LegacyMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        ob_start();

        $this->boot();

        $headers = headers_list();
        header_remove();

        $response = new HtmlResponse(ob_get_clean());
        $response->withStatus(http_response_code());

        foreach ($headers as $header) {
            $pieces = explode(':', $header);
            $headerName = array_shift($pieces);
            $response->withHeader($headerName, trim(implode(':', $pieces)));
        }

        return $response;
    }

    protected function boot()
    {
        defined('APPLICATION_PATH')
        || define('APPLICATION_PATH',
            (getenv('APPLICATION_PATH') ? getenv('APPLICATION_PATH')
                : 'production'));

        defined('APPLICATION_ENV')
        || define('APPLICATION_ENV',
            (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                : 'production'));

        // Create application, bootstrap, and run
        $application = new \Zend_Application(
            getenv('APPLICATION_ENV'),
            getenv('APPLICATION_PATH') . '/configs/application.ini'
        );
        $application->bootstrap()
            ->run();
    }
}
