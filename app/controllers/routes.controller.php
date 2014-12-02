<?php

$app->get('/inicio/:id', function($id) use($app){
   $pokeball = Pokeball::where('trainer_id',$id)->get();
   $trainer = Trainer::where('id',$id)->first();
   foreach ($pokeball as $pk) {
    $notices[] =  array(
          'id' => $pk->id,
          'specie' => $pk->specie,
          'image' => $pk->image,
          'alias' => $pk->alias,
          'status' => $pk->status,
          'trainer' => $trainer->username,
          );
      $i++;
   }

   echo json_encode($notices);

});

$app->get('/regiones(/:id)', function($id = null) use($app){
  if($id == null){
    $region = Region::all();
    echo $region->toJson();
  }else{
    $region = Region::where('id',$id)->first();
    echo $region->toJson();
  }
});

$app->get('/centros(/:id)', function($id = null) use($app){
  if($id == null){
    $centro = Center::with('region_id')->get();
    echo $centro->toJson();
  }else{
    $centro = Center::with('region_id')->where('id',$id)->first();
    echo $centro->toJson();
  }
});

$app->get('/region(/:id)/centros', function($id = null) use($app){
  $centro = Region::with('centers')->where('id',$id)->first();
  echo $centro->toJson();
});

$app->get('/tipos(/:id)', function($id = null) use($app){
  if($id == null){
    $tipo = Type::with('pokemons')->get();
    echo $tipo->toJson();
  }else{
    $tipo = Type::where('id',$id)->first();
    echo $tipo->toJson();
  }
});

$app->get('/habilidades(/:id)', function($id = null) use($app){
  if($id == null){
    $habilidad = Ability::all();
    echo $habilidad->toJson();
  }else{
    $habilidad = Ability::where('id',$id)->first();
    echo $habilidad->toJson();
  }
});

$app->get('/status(/:id)', function($id = null) use($app){
  if($id == null){
    $status = Status::all();
    echo $status->toJson();
  }else{
    $status = Status::where('id',$id)->first();
    echo $status->toJson();
  }
});

$app->get('/pokemon(/:id)', function($id = null) use($app){
  if($id == null){
    $pokemon = Pokemon::all();
    echo $pokemon->toJson();
    /*$i = 0;
    foreach ($pokemon as $p) {
      $data[$i] =  array(
          'id' => $p->id,
          'species' => $p->species,
          'image' => $p->image,
          'region_id' => $p->region_id,
          'hit_points' => $p->hit_points,
          'attack' => $p->attack,
          'defense' => $p->defense,
          'speed' => $p->speed,
          'evasion' => $p->evasion,
          'accuracy' => $p->accuracy,
          );pokemon
      $i++;
    }
    $response['pokemon'] = $data;
    echo json_encode($response); */
  }else{
    $pokemon = Pokemon::where('id',$id)->first();
    echo $pokemon->toJson();
  }
});

$app->get('/evoluciones(/:id)', function($id = null) use($app){
  if($id == null){
    $pokemon = Evolution::with('pokemon_id','pokemon_id_e')->get();
    echo $pokemon->toJson();
  }else{
    $pokemon = Evolution::with('pokemon_id','pokemon_id_e')->where('id',$id)->first();
    echo $pokemon->toJson();
  }
});

$app->get('/entrenadores(/:id)', function($id = null) use($app){
  if($id == null){
    $trainer = Trainer::with('region_id','region_id_actual')->get();
    echo $trainer->toJson();
  }else{
    $trainer = Trainer::with('region_id','region_id_actual')->where('id',$id)->first();
    echo $trainer->toJson();
  }
});


$app->post('/entrenadores', function() use($app) {
  $post = (object) $app->request->post();
  $trainer = new Trainer();
  $trainer->name = $post->name;
  $trainer->last_name = $post->last_name;
  $trainer->image = $post->image;
  $trainer->username = $post->username;
  $trainer->password = $post->password;
  $trainer->birthday = $post->birthday;
  $trainer->region_id = $post->region_id;
  $trainer->gender = $post->gender;
  $trainer->leader = $post->leader;
  $trainer->region_id_actual = $post->region_id_actual;
  if($trainer->save()) {
    $trainer['status'] = '1';
    $trainer['id'] = $trainer->id;
    $trainer['username'] = $trainer->username;
    $trainer['password'] = $trainer->password;
    $trainer['name'] = $trainer->name;
    $trainer['last_name'] = $trainer->last_name;
  } else {
    $trainer['status'] = '0';
  }

  echo json_encode($trainer);

});


$app->get('/pokebolas(/:id)', function($id = null) use($app){
  if($id == null){
    $pokebola = Pokeball::all();
    echo $pokebola->toJson();
  }else{
    $pokebola = Pokeball::where('trainer_id',$id)->get();
    echo $pokebola->toJson();
  }
});

$app->post('/pokebola', function() use($app){
  $post = (object) $app->request->post();
  $pokeball = new Pokeball();
  $pokeball->trainer_id = $post->trainer_id;
  $pokeball->pokemon_id = $post->pokemon_id;
  $pokeball->alias = $post->alias;
  $pokeball->gender = $post->gender;
  $pokeball->level = 1;

  $id = $post->pokemon_id;
  $pokemon = Pokemon::where('id',$id)->first();
  $pokeball->url = $pokemon->url;
  $pokeball->specie = $pokemon->species;
  $pokeball->image = $pokemon->image;
  $pokeball->hit_points = $pokemon->hit_points;
  $pokeball->attack = $pokemon->attack;
  $pokeball->defense = $pokemon->defense;
  $pokeball->speed = $pokemon->speed;
  $pokeball->evasion = $pokemon->evasion;
  $pokeball->accuracy = $pokemon->accuracy;

  $pokeball->status_id = rand(1,8);
  $status = Status::where('id',$pokeball->status_id)->first();
  $pokeball->status = $status->name;

  if($pokeball->save()) {
    $pokeball['status'] = 1;
  } else {
    $pokeball['status'] = 0;
  }

  echo json_encode($pokeball);

});

$app->get('/regeneradores(/:id)', function($id = null) use($app){
  if($id == null){
    $regenerador = Regenerator::with('center_id')->get();
    echo $regenerador->toJson();
  }else{
    $regenerador = Regenerator::with('center_id')->where('id',$id)->first();
    echo $regenerador->toJson();
  }
});

$app->get('/habitaciones(/:id)', function($id = null) use($app){
  if($id == null){
    $habitacion = Room::with('center_id')->get();
    echo $habitacion->toJson();
  }else{
    $habitacion = Room::with('center_id')->where('id',$id)->first();
    echo $habitacion->toJson();
  }
});
