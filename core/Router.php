<?php

class Router {
    private array $routes = [];

    public function get(string $uri, callable $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, callable $action) {
        $this->routes['POST'][$uri] = $action;
    }

public function dispatch(string $uri, string $method) {
    $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/');

    foreach ($this->routes[$method] as $route => $action) {
        // /user/:id => /user/(?P<id>[^/]+)
        $pattern = preg_replace('#/:([\w]+)#', '/(?P<$1>[^/]+)', $route);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $uri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return call_user_func_array($action, $params);
        }
    }

    http_response_code(404);
    include_once __DIR__ . '/../app/Views/not_found.php';
}

}
