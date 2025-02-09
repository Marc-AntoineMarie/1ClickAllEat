<?php

namespace App\Http\Controllers;  

use Illuminate\Http\Request; 
use App\Models\Restaurants;

Class RestaurantController extends Controller
{   
    public function index(){
    
        //récupération des donnnées de la table restaurants
        $restaurants = Restaurants::all();

        //affichage des données sur la page
        return view('restaurants.show', [
            'restaurants' => Restaurants::all() 
        ]);
    }

    public function create() {

        return view('restaurants.create');
    }

    public function store(Request $request) {
        Restaurants::create( $request->all() );
        
        return redirect()->route('restaurants.index');
    }

    public function edit($id) {
        return view('restaurants.edit', [
            'restaurant' => Restaurants::findOrFail($id)
        ]);
    }

    public function update (Request $request, $id) {
        $restaurant = Restaurants::findOrFail($id);

        $restaurant->nom = $request->get('nom');
        $restaurant->description = $request->get('description');
        $restaurant->place_max = $request->get('place_max');
        $restaurant->save();

        return redirect()->route('restaurants.index');
    }

    public function destroy (Request $request, $id) {
        if($request->get('id') == $id) {
            Restaurants::destroy($id);
        } else {
            echo ("restaurants introuvable");
        }
        return redirect()->route('restaurants.index');
    }
}

