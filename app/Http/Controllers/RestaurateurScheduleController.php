<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAvailability;
use App\Models\EmployeeSchedule;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurateurScheduleController extends Controller
{
    /**
     * Afficher le dashboard de planning pour un restaurateur
     */
    public function index()
    {
        $user = Auth::user();
        $restaurants = Restaurant::where('owner_id', $user->id)->get();
        
        if ($restaurants->isEmpty()) {
            return view('restaurateur.schedules.no-restaurants');
        }
        
        // Par défaut, sélectionner le premier restaurant
        $selectedRestaurant = $restaurants->first();
        $restaurantId = $selectedRestaurant->id;
        
        return $this->showRestaurantSchedule($restaurantId);
    }
    
    /**
     * Afficher le planning pour un restaurant spécifique
     */
    public function showRestaurantSchedule($restaurantId)
    {
        $user = Auth::user();
        $restaurants = Restaurant::where('owner_id', $user->id)->get();
        
        // Vérifier que l'utilisateur est bien le propriétaire du restaurant
        $selectedRestaurant = $restaurants->where('id', $restaurantId)->first();
        
        if (!$selectedRestaurant) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à ce restaurant.');
        }
        
        // Récupérer les employés qui ont des disponibilités pour ce restaurant
        $employeeIds = EmployeeAvailability::where('restaurant_id', $restaurantId)
            ->distinct('user_id')
            ->pluck('user_id');
            
        $employees = User::whereIn('id', $employeeIds)->get();
        
        // Récupérer la semaine en cours (du lundi au dimanche)
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();
        
        // Récupérer les disponibilités et plannings pour la semaine en cours
        $availabilities = EmployeeAvailability::where('restaurant_id', $restaurantId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->with('user')
            ->get();
            
        $schedules = EmployeeSchedule::where('restaurant_id', $restaurantId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->with(['user', 'creator'])
            ->get();
            
        return view('restaurateur.schedules.index', compact(
            'restaurants', 
            'selectedRestaurant', 
            'employees', 
            'availabilities', 
            'schedules', 
            'startOfWeek', 
            'endOfWeek'
        ));
    }
    
    /**
     * Changer la semaine du planning (précédente ou suivante)
     */
    public function changeWeek(Request $request, $restaurantId)
    {
        $request->validate([
            'week_start' => 'required|date',
            'direction' => 'required|in:prev,next'
        ]);
        
        $startOfWeek = Carbon::parse($request->week_start);
        
        if ($request->direction === 'prev') {
            $newStartOfWeek = $startOfWeek->copy()->subWeek()->startOfWeek();
        } else {
            $newStartOfWeek = $startOfWeek->copy()->addWeek()->startOfWeek();
        }
        
        $newEndOfWeek = $newStartOfWeek->copy()->endOfWeek();
        
        // Récupérer les données pour la nouvelle semaine
        // Le reste du code est similaire à showRestaurantSchedule
        
        // Rediriger avec les nouvelles dates en paramètres
        return redirect()->route('restaurateur.schedules.week', [
            'restaurantId' => $restaurantId,
            'start_date' => $newStartOfWeek->format('Y-m-d'),
            'end_date' => $newEndOfWeek->format('Y-m-d'),
        ]);
    }
    
    /**
     * Afficher le formulaire pour créer un nouveau planning
     */
    public function create($restaurantId, $availabilityId = null)
    {
        $user = Auth::user();
        $restaurant = Restaurant::where('owner_id', $user->id)
            ->where('id', $restaurantId)
            ->firstOrFail();
            
        if ($availabilityId) {
            $availability = EmployeeAvailability::where('restaurant_id', $restaurantId)
                ->where('id', $availabilityId)
                ->with('user')
                ->firstOrFail();
                
            return view('restaurateur.schedules.create', compact('restaurant', 'availability'));
        }
        
        // Si pas d'availability_id, afficher le formulaire pour créer un planning à partir de zéro
        $employees = User::whereHas('role', function ($query) {
            $query->where('name', 'employee');
        })->get();
        
        return view('restaurateur.schedules.create', compact('restaurant', 'employees'));
    }
    
    /**
     * Enregistrer un nouveau planning
     */
    public function store(Request $request, $restaurantId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'availability_id' => 'nullable|exists:employee_availabilities,id'
        ]);
        
        // Vérifier que l'utilisateur est bien le propriétaire du restaurant
        $restaurant = Restaurant::where('owner_id', Auth::id())
            ->where('id', $restaurantId)
            ->firstOrFail();
        
        // Vérifier si l'employé a déjà un planning pour cette date et créneau horaire
        $existingSchedule = EmployeeSchedule::where('user_id', $request->user_id)
            ->where('restaurant_id', $restaurantId)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->first();
            
        if ($existingSchedule) {
            return back()->withInput()->with('error', 'Cet employé a déjà un planning qui chevauche ce créneau horaire.');
        }
        
        // Si une disponibilité est spécifiée, vérifier qu'elle correspond au planning
        if ($request->filled('availability_id')) {
            $availability = EmployeeAvailability::findOrFail($request->availability_id);
            
            // Vérifier que la disponibilité appartient bien au restaurant et à l'employé
            if ($availability->restaurant_id != $restaurantId || $availability->user_id != $request->user_id) {
                return back()->withInput()->with('error', 'La disponibilité sélectionnée n\'est pas valide.');
            }
            
            // Vérifier que le planning respecte les horaires de disponibilité
            $availabilityStart = Carbon::parse($availability->date . ' ' . $availability->start_time);
            $availabilityEnd = Carbon::parse($availability->date . ' ' . $availability->end_time);
            $scheduleStart = Carbon::parse($request->date . ' ' . $request->start_time);
            $scheduleEnd = Carbon::parse($request->date . ' ' . $request->end_time);
            
            if ($scheduleStart->lt($availabilityStart) || $scheduleEnd->gt($availabilityEnd)) {
                return back()->withInput()->with('error', 'Les horaires du planning doivent être inclus dans les horaires de disponibilité.');
            }
        }
        
        EmployeeSchedule::create([
            'user_id' => $request->user_id,
            'restaurant_id' => $restaurantId,
            'availability_id' => $request->availability_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'confirmed',
            'created_by' => Auth::id()
        ]);
        
        return redirect()->route('restaurateur.schedules.restaurant', ['restaurantId' => $restaurantId])
            ->with('success', 'Planning créé avec succès.');
    }
    
    /**
     * Modifier un planning existant
     */
    public function edit($restaurantId, $scheduleId)
    {
        $user = Auth::user();
        $restaurant = Restaurant::where('owner_id', $user->id)
            ->where('id', $restaurantId)
            ->firstOrFail();
            
        $schedule = EmployeeSchedule::where('restaurant_id', $restaurantId)
            ->where('id', $scheduleId)
            ->with(['user', 'availability'])
            ->firstOrFail();
            
        return view('restaurateur.schedules.edit', compact('restaurant', 'schedule'));
    }
    
    /**
     * Mettre à jour un planning existant
     */
    public function update(Request $request, $restaurantId, $scheduleId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        
        // Vérifier que l'utilisateur est bien le propriétaire du restaurant
        $restaurant = Restaurant::where('owner_id', Auth::id())
            ->where('id', $restaurantId)
            ->firstOrFail();
            
        $schedule = EmployeeSchedule::where('restaurant_id', $restaurantId)
            ->where('id', $scheduleId)
            ->firstOrFail();
            
        // Vérifier si le planning modifié chevauche un autre planning existant
        $existingSchedule = EmployeeSchedule::where('user_id', $schedule->user_id)
            ->where('restaurant_id', $restaurantId)
            ->where('date', $request->date)
            ->where('id', '!=', $scheduleId)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->first();
            
        if ($existingSchedule) {
            return back()->withInput()->with('error', 'Cet employé a déjà un planning qui chevauche ce créneau horaire.');
        }
        
        // Si le planning est basé sur une disponibilité, vérifier que les nouveaux horaires respectent la disponibilité
        if ($schedule->availability_id) {
            $availability = EmployeeAvailability::findOrFail($schedule->availability_id);
            
            $availabilityStart = Carbon::parse($availability->date . ' ' . $availability->start_time);
            $availabilityEnd = Carbon::parse($availability->date . ' ' . $availability->end_time);
            $scheduleStart = Carbon::parse($request->date . ' ' . $request->start_time);
            $scheduleEnd = Carbon::parse($request->date . ' ' . $request->end_time);
            
            if ($scheduleStart->lt($availabilityStart) || $scheduleEnd->gt($availabilityEnd)) {
                return back()->withInput()->with('error', 'Les horaires du planning doivent être inclus dans les horaires de disponibilité.');
            }
        }
        
        $schedule->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        
        return redirect()->route('restaurateur.schedules.restaurant', ['restaurantId' => $restaurantId])
            ->with('success', 'Planning mis à jour avec succès.');
    }
    
    /**
     * Supprimer un planning
     */
    public function destroy($restaurantId, $scheduleId)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du restaurant
        $restaurant = Restaurant::where('owner_id', Auth::id())
            ->where('id', $restaurantId)
            ->firstOrFail();
            
        $schedule = EmployeeSchedule::where('restaurant_id', $restaurantId)
            ->where('id', $scheduleId)
            ->firstOrFail();
            
        // Vérifier que la date n'est pas dans le passé
        if (Carbon::parse($schedule->date)->isPast()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer un planning passé.');
        }
        
        $schedule->delete();
        
        return redirect()->route('restaurateur.schedules.restaurant', ['restaurantId' => $restaurantId])
            ->with('success', 'Planning supprimé avec succès.');
    }
}
