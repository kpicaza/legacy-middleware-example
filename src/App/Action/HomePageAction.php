<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomePageAction implements MiddlewareInterface
{
    private $router;

    private $template;

    public function __construct(
        RouterInterface $router,
        TemplateRendererInterface $template
    ) {
        $this->router = $router;
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $data = [];

        $data['routerName'] = 'Aura.Router';
        $data['routerDocs'] = 'http://auraphp.com/packages/2.x/Router.html';

        $data['templateName'] = 'Twig';
        $data['templateDocs'] = 'http://twig.sensiolabs.org/documentation';

        return new HtmlResponse($this->template->render('app::home-page', $data));
    }
}
