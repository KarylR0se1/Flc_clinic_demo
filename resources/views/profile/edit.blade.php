@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
</style>

<div class="container mx-auto py-10 px-4">
    <div class="max-w-2xl mx-auto bg-white shadow-xl rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 py-6 px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-white tracking-wide">Edit Profile</h2>
        </div>

        <div class="p-6 md:p-8 space-y-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-center font-medium shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Profile Picture -->
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Profile Picture</h3>

                    <img id="profile-preview" 
                         src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://via.placeholder.com/150' }}" 
                         alt="Profile Picture" 
                         class="w-40 h-40 md:w-48 md:h-48 rounded-full object-cover border-4 border-gray-200 shadow-lg mx-auto mb-4">

                    <input type="file" name="profile_picture" accept="image/*" 
                           onchange="previewProfilePicture(event)" 
                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0 file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-gray-500 text-xs mt-2">Max size: 2MB. Allowed: jpg, jpeg, png.</p>
                </div>

                <!-- Personal Information -->
                <div class="space-y-5">
                    <h3 class="text-xl font-semibold text-gray-700">Personal Information</h3>

                    <div>
                        <label class="block mb-1 font-medium text-gray-600">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium text-gray-600">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <!-- Patient Details -->
                @if($user->patient)
                <div class="space-y-5">
                    <h4 class="text-lg font-semibold text-gray-700">Patient Details</h4>

                    <div>
                        <label class="block mb-1 font-medium text-gray-600">Birthdate</label>
                        <input type="date" name="birthdate" value="{{ old('birthdate', $user->patient->birthdate) }}" 
                               class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium text-gray-600">Sex</label>
                        <select name="sex" 
                                class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Male" {{ ($user->patient->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ ($user->patient->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium text-gray-600">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->patient->phone_number) }}" 
                               class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium text-gray-600">Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->patient->address) }}" 
                               class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                @endif

                <!-- Doctor Details -->
                @if($user->doctor)
                <div class="space-y-5">
                    <h4 class="text-lg font-semibold text-gray-700">Doctor Details</h4>

                    <div>
                        <label class="block mb-1 font-medium text-gray-600">Specialization</label>
                        <input type="text" name="specialization" value="{{ old('specialization', $user->doctor->specialization) }}" 
                               class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium text-gray-600">License Number</label>
                        <input type="text" name="license_number" value="{{ old('license_number', $user->doctor->license_number) }}" 
                               class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                @endif

                <!-- Save Button -->
                <div class="text-center">
                    <button type="submit" class="w-full bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold text-lg shadow-md">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewProfilePicture(event) {
        const reader = new FileReader();
        reader.onload = function(){
            document.getElementById('profile-preview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
