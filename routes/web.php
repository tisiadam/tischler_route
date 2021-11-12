<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/doctor-house', function () {
    return view('Nemcsak az emberek megalázásával lehet a gőzt kiereszteni; mondják,
                        hogy a bowling jobb még ennél is.');
});
Route::get('/uvegtigris/csoki',function(){
    return view('Mennyire vagy túsz? Sörhöz odaférsz?');
});
Route::get('/uvegtigris/lali',function (){
    return view('Az egybubis az egy kicsit drágább, mert hát abból ki kellett vennem
                       a többi bubit');

});
Route::get('/modern-family',function (){
    return view(
        '– Mindig is tudtuk hol a határ - bólintott Fred
              - És csak óvatosan léptük át - tette hozzá George.');

})->name('modern-family');

Route::get('/harry-potter/fred-es-george',function (){
    return view('A siker mindig 1 százalék ihlet, plusz 98 százalék verejték, végül
                        pedig 2 százalék odafigyelés.');

})->name('harry-potter.fred-es-george');

Route::get('/harry-potter/hermione',function (){
    return view('Még egy ilyen remek ötlet, és mindhárman meghalunk, vagy akár ki
                        is csaphatnak!.');

})->name('harry-potter.hermione');
route::get('/naptar/{nap}',function($nap){
   if ($nap=='ma'){
       $d=datetime('NOW');
       return $d->format('Y-m-d');
   } elseif(nap=="holnap"){
       $i=new DateTime('NOW');
       $d=new DateInterval('P1D');
       return$d->add($i)->format('Y-m-d');
   }elseif (nap=="tegnap"){
       $i=new DateTime('NOW');
       $d=new DateInterval('P1D');
       return$d->add($i)->format('Y-m-d');
   }
});



