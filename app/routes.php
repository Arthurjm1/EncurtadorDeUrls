<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Models\Url;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->post('/', function (Request $request, Response $response, array $args) {
        
        $db = $this->get('db');
        $url = $request->getParsedBody()['url'];
        $urlModel = new Url;

        $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $urlCurta = substr(str_shuffle($str), 0, 5);

        $urlModel->__set('url_original', $url)->__set('url_curta', $urlCurta);        
        $urlModel->insertUrl($db);     

        $response->getBody()->write(json_encode(['newUrl' => 'http://localhost:8080/'.$urlCurta]));

        return $response->withHeader('Content-type', 'application/json');
    });

    $app->get('/{url}', function(Request $request, Response $response, array $args){

        $db = $this->get('db');
        $urlCurta = $args['url'];
        $urls = Url::getUrls($db);
        $destino = null;

        foreach($urls as $url){
            if($url['url_curta'] == $urlCurta){
                $destino =  $url['url_original'];
            }
        }     

        if(is_null($destino)){
            return $response->withStatus(404);
        }
        return $response->withHeader('Location', $destino)->withStatus(302);
    });

    $app->group('/users/get', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
