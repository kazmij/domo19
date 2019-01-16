<?php


namespace Application\Sonata\MainBundle\Twig;

use Application\Sonata\UserBundle\Admin\Entity\DirectorAdmin;
use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\MainBundle\Model\RouteableInterface;

class Extension extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('arrayFilter', [$this, 'arrayFilter']),
            new \Twig_SimpleFilter('pluralizationMode', [$this, 'pluralizationMode'])
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('static', function ($class, $property) {
                if (property_exists($class, $property)) {
                    return $class::$$property;
                }
                return null;
            }),
            new \Twig_SimpleFunction('getParameter', [$this, 'getParameter']),
            new \Twig_SimpleFunction('inArray', [$this, 'inArray']),
            new \Twig_Function('url_to', [$this, 'getUrl']),
            new \Twig_Function('path_to', [$this, 'getPath']),
            new \Twig_Function('goBack', [$this, 'goBack']),
            new \Twig_Function('render_ajax', [$this, 'renderAjax']),
            new \Twig_Function('isHome', [$this, 'isHome'])
        );
    }

    public function getParameter($name, $subkey = null)
    {

        if ($this->container->hasParameter($name)) {

            $parameter = $this->container->getParameter($name);

            if ($subkey) {
                $parameter = isset($parameter[$subkey]) ? $parameter[$subkey] : null;
            }

            return $parameter;
        }

        return false;
    }

    public function inArray($needle, $haystack)
    {

        return in_array($needle, $haystack);
    }

    public function arrayFilter($arr)
    {

        return array_filter($arr);
    }

    public function getPath(RouteableInterface $entity, $params = array())
    {
        if ($params && !is_array($params)) {
            $params = array($params);
        }

        $params = array_merge($entity->getRouteParams(), $params);

        return $this->container->get('router')->generate($entity->getRouteName(), $params, false);
    }

    public function getUrl(RouteableInterface $entity, $params = array())
    {
        if ($params && !is_array($params)) {
            $params = array($params);
        }

        $params = array_merge($entity->getRouteParams(), $params);

        return $this->container->get('router')->generate($entity->getRouteName(), $params, true);
    }

    public function pluralizationMode($number)
    {
        if ($number == 1) {
            return 1;
        } elseif (in_array($number % 10, [
            2, 3, 4
        ])) {
            return 2;
        } elseif (in_array($number % 10, [
            0, 1, 5, 6, 7, 8, 9
        ])) {
            return 3;
        }
    }

    public function goBack($default)
    {
        $router = $this->container->get('router');
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $referer = $request->server->get('HTTP_REFERER');
        $host = $request->getSchemeAndHttpHost();

        if ($referer && preg_match('/' . $request->server->get('HTTP_HOST') . '/im', $referer) &&
            !preg_match('|' . $request->getUri() . '|im', $referer)
        ) {
            $referer = str_replace($host, '', $referer);
            $match = $router->match($referer);
            $routerReferer = $match['_route'];
            $currentPath = $request->getPathInfo();
            $match = $router->match($currentPath);
            if ($match['_route'] != $routerReferer) {
                return $referer;
            }
        }

        return $default;
    }

    public function renderAjax($path)
    {

        return $this->container->get('templating')->render('ApplicationSonataMainBundle:Default:renderAjax.html.twig', [
            'path' => $path
        ]);
    }

    public function isHome() {
        $request = $this->container->get('request_stack')->getMasterRequest();
        
        return $request->getPathInfo() === '/';
    }
}