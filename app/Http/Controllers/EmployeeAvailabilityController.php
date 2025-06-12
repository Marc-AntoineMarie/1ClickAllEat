<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAvailability;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeAvailabilityController extends Controller
{
    /**
     * Afficher le dashboard de l'employé avec son calendrier
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get the current week dates
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        
        // Generate dates for the next 7 days
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dates[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('l'),
                'formatted' => $date->format('d M')
            ];
        }
        
        // Get employee availabilities
        $availabilities = EmployeeAvailability::where('user_id', $user->id)
            ->whereDate('date', '>=', $startOfWeek)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });
        
        // Get restaurants where employee works
        $restaurants = Restaurant::all();
        
        return view('employee.dashboard', compact('dates', 'availabilities', 'restaurants'));
    }
    
    /**
     * Afficher le formulaire pour ajouter une nouvelle disponibilité
     */
    public function create()
    {
        $user = Auth::user();
        $restaurantIds = EmployeeAvailability::where('user_id', $user->id)
            ->distinct('restaurant_id')
            ->pluck('restaurant_id');
            
        $restaurants = Restaurant::whereIn('id', $restaurantIds)->get();
        
        // Si l'employé n'est associé à aucun restaurant, redirection avec message d'erreur
        if ($restaurants->isEmpty()) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'Vous n\'êtes associé à aucun restaurant. Contactez un administrateur.');
        }
        
        return view('employee.availabilities.create', compact('restaurants'));
    }
    
    /**
     * Enregistrer une nouvelle disponibilité
     */
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        
        // Create the availability
        EmployeeAvailability::create([
            'user_id' => Auth::user()->id,
            'restaurant_id' => $request->restaurant_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Disponibilité ajoutée avec succès');
    }
    
    /**
     * Supprimer une disponibilité
     */
    public function destroy($id)
    {
        $availability = EmployeeAvailability::findOrFail($id);
        
        // Check if this availability belongs to the logged-in user
        if ($availability->user_id !== Auth::user()->id) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'Non autorisé');
        }
        
        $availability->delete();
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Disponibilité supprimée avec succès');
    }
}
