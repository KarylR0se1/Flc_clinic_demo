<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ config('app.name', 'FLC Clinic') }} - Email Verification</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-center">Verify Your Email Address</h2>

    @if (session('resent'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            A fresh verification link has been sent to your email address.
        </div>
    @endif

    <p class="mb-2">
        Before proceeding, please check your email for a verification link.
    </p>
    <p class="mb-4">
        If you did not receive the email,
    </p>

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="w-full p-2 bg-blue-600 text-white rounded">
            Click here to request another
        </button>
    </form>
</div>

</body>
</html>
