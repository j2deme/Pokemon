<?php
session_cache_limiter(false);
@session_start();
date_default_timezone_set('America/Mexico_City');

require 'vendor/autoload.php';
include_once 'app/vars.inc.php';
include_once APP_FOLDER.'config.php';

//Load all the models
include_once MODELS_FOLDER."Elegant.php";
foreach(glob(MODELS_FOLDER.'*.php') as $model) {
  if($model != "Elegant.php")
    include_once $model;
}

$auth = function ($app) {
  return function () use ($app) {
    if (!isset($_SESSION['user'])) {
      //$_SESSION['redirectTo'] = $app->request()->getPathInfo()
      $app->redirect($app->urlFor('root'));
    }
  };
};

$app->contentType('text/html; charset=utf-8');

$app->hook('slim.before.dispatch', function() use ($app) {
  $user = null;
  if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
  }
  $app->view()->appendData(array('user' => $user));
});

$app->get('/', function() use($app){
  //$app->render('index.twig');
  echo "Pokémon";
})->name('root');

$app->post('/login', function() use($app){
  $post = (object) $app->request()->post();
  $nombre = (isset($post->nombre)) ? $post->nombre : '';
  $password = (isset($post->password)) ? $post->password : '';

  $errors = array();
  $centro_pokemon = Centro_Pokemon::where('nombre','=',$nombre)->first();
  if(!is_null($centro_pokemon)){
    if($centro_pokemon->password == $password){
      $_SESSION['user'] = $centro_pokemon;
    } else {
      $errors['password'] = $app->lang->passwordError;
    }
  } else {
    $app->flash('nombre', $nombre);
  }

  if(count($errors) > 0) {
    $app->redirect($app->urlFor('login'));
  }

  $app->redirect($app->urlFor('home'));
})->name('login-post');

$app->get('/logout', function() use($app){
  unset($_SESSION['user']);
  $app->view()->setData('user', null);
  $app->redirect($app->urlFor('root'));
})->name('logout');

//Load all the controllers
foreach(glob(CONTROLLERS_FOLDER.'*.php') as $router) {
  include_once $router;
}

$app->run();
?>
