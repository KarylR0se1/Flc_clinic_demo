<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-start pt-10">

    <div class="bg-white rounded shadow-md p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">My Personal Information</h2>

        <div class="flex flex-col items-center mb-4">
            @php
                $profilePicture = optional(auth()->user()->patient)->profile_picture
                    ? asset('storage/' . auth()->user()->patient->profile_picture)
                    : asset('images/default-profile.png');
            @endphp
            <img src="{{ $profilePicture }}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover mb-2">
            <span class="font-semibold text-lg">{{ auth()->user()->name }}</span>
            <span class="text-gray-600">{{ auth()->user()->email }}</span>
        </div>

        <div class="space-y-2">
            <div><strong>Birth Date:</strong> {{ optional(auth()->user()->patient)->birthdate ?? 'N/A' }}</div>
            <div><strong>Sex:</strong> {{ optional(auth()->user()->patient)->sex ?? 'N/A' }}</div>
            <div><strong>Phone Number:</strong> {{ optional(auth()->user()->patient)->phone_number ?? 'N/A' }}</div>
            <div><strong>Address:</strong> {{ optional(auth()->user()->patient)->address ?? 'N/A' }}</div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Edit Profile
            </a>
        </div>
    </div>

</body>
</html>
