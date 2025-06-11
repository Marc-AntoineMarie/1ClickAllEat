<?php
// AuthController désactivé : Breeze gère désormais l'authentification.
// Ancien code supprimé pour éviter tout conflit et toute erreur PHP.


//         if (Auth::attempt($credentials)) {
//             $request->session()->regenerate();
//             if (Auth::user()->role->name === 'restaurateur') {
//                 return redirect()->route('restaurateur.dashboard');
//             }
// */ elseif (Auth::user()->role->name === 'admin') {
//                 return redirect()->route('admin.dashboard');
//             }
// */
//             return redirect()->intended('dashboard');
//         }
// */

//         return back()->withErrors([
//             'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
//         ])->onlyInput('email');
//     }
// */

//     public function showRegister()
//     {
//         return view('auth.register');
//     }
// */

//     public function register(Request $request)
//     {
//         $validated = $request->validate([
//             'name' => ['required', 'string', 'max:255'],
//             'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//             'password' => ['required', 'string', 'min:8', 'confirmed'],
//             'telephone' => ['nullable', 'string', 'max:20'],
//             'role_id' => ['required', 'exists:roles,id'],
//         ]);

//         $user = User::create([
//             'name' => $validated['name'],
//             'email' => $validated['email'],
//             'password' => Hash::make($validated['password']),
//             'telephone' => $validated['telephone'],
//             'role_id' => $validated['role_id'],
//         ]);

//         Auth::login($user);

//         if ($user->role->name === 'restaurateur') {
//             return redirect()->route('restaurateur.dashboard');
//         }
// */ elseif ($user->role->name === 'admin') {
//             return redirect()->route('admin.dashboard');
//         }
// */
//         return redirect('/dashboard');
//     }
// */

//     public function logout(Request $request)
//     {
//         Auth::logout();

//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

//         return redirect('/');
//     }
// */
// }

