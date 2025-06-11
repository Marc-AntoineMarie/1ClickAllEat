<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                
                    <style>
    .profile-container {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        padding: 32px 24px;
        margin-bottom: 32px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .profile-title {
        font-weight: 700;
        font-size: 2rem;
        color: #dc3545;
        margin-bottom: 24px;
        text-align: center;
    }
    .profile-section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 12px;
    }
    .profile-section {
        margin-bottom: 32px;
    }
</style>
<div class="profile-container">
    <div class="profile-title">Mon Profil</div>
    <div class="profile-section">
        <div class="profile-section-title">Informations du compte</div>
        @include('profile.partials.update-profile-information-form')
    </div>
    <div class="profile-section">
        <div class="profile-section-title">Modifier le mot de passe</div>
        @include('profile.partials.update-password-form')
    </div>
    <div class="profile-section">
        <div class="profile-section-title text-danger">Supprimer le compte</div>
        @include('profile.partials.delete-user-form')
    </div>
</div>
        </div>
    </div>
</x-app-layout>
