<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FLC Clinic Appointments & Records Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);
      min-height: 100vh;
    }
    
    .card-shadow {
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    
    .input-focus:focus {
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
      border-color: #3b82f6;
    }
    
    .btn-gradient {
      background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
      transition: all 0.3s ease;
    }
    
    .btn-gradient:hover {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
    }
    
    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      color: #64748b;
      font-size: 0.875rem;
    }
    
    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #e2e8f0;
    }
    
    .divider::before {
      margin-right: 0.5em;
    }
    
    .divider::after {
      margin-left: 0.5em;
    }
    
    .logo-container {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background:  #3b82f6 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 80px;
      box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
      margin: 0 auto 20px;
    }
  </style>
</head>
<body class="flex flex-col min-h-screen">

  <!-- Header -->
<header class="bg-white shadow-sm py-4">
  <div class="container mx-auto px-6 flex justify-center items-center">
    <div class="flex items-center space-x-3">

      <h1 class="text-blue-800 text-lg md:text-xl font-bold text-center">
        FLC Clinic Appointments and Records Management System
  </h1>
    </div>
  </div>
</header>


  <!-- Main Content -->
  <main class="flex-grow flex items-center justify-center py-7 px-4 sm:px-4 lg:px-4">
    <div class="max-w-5xl w-full flex flex-col md:flex-row items-center gap-10">
      
      <!-- Left: Branding Section -->
      <div class="w-full md:w-2/5 flex flex-col items-center text-center md:text-left">
        <img src="{{ asset('build/assets/photo_2025-09-08_21-04-35.jpg') }}" 
     alt="FLC Clinic Logo" 
     class="w-48 h-48 rounded-full object-cover shadow-xl border-4 border-white mx-auto mb-6">
        
        <h1 class="text-3xl md:text-4xl font-bold text-blue-900 mb-4">FLC Clinic</h1>
        <p class="text-blue-700 text-lg mb-6">Your Health is Our Priority</p>
        
        <div class="hidden md:block space-y-4">
          <div class="flex items-center justify-center md:justify-start text-blue-800">
            <div class="bg-blue-100 p-3 rounded-full mr-3">
              <i class="fas fa-map-marker-alt text-blue-600"></i>
            </div>
            <p>Bontoc, Mountain Province<br>Cordillera Administrative Region</p>
          </div>
          
          <div class="flex items-center justify-center md:justify-start text-blue-800">
            <div class="bg-blue-100 p-3 rounded-full mr-3">
              <i class="fas fa-clock text-blue-600"></i>
            </div>
            <p>Mon - Sat: 8:00 AM - 4:00 PM<br>Sunday: Emergency Only</p>
          </div>
        </div>
      </div>
      
      <!-- Right: Login Form -->
      <div class="w-full md:w-3/5">
        <div class="bg-white rounded-2xl card-shadow p-8 md:p-10">
          <div class="text-center mb-2">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Welcome Back</h2>
            <p class="text-gray-600 mt-2">Sign in to access your appointments and medical records</p>
          </div>
          @if(session('login_error'))
    <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-400">
        {{ session('login_error') }}
    </div>
@endif

          <form method="POST" action="{{ route('login.submit') }}" class="mt-8 space-y-6">
            @csrf
            
            <div class="rounded-md shadow-sm space-y-4">
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                  </div>
                  <input id="email" name="email" type="email" required 
                         class="py-3 px-4 pl-10 block w-full border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500"
                         placeholder="Enter your email">
                </div>
              </div>
              
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                  </div>
                  <input id="password" name="password" type="password" required 
                         class="py-3 px-4 pl-10 block w-full border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500"
                         placeholder="Enter your password">
                </div>
              </div>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox" 
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
              </div>

              <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
              </div>
            </div>

            <div>
              <button type="submit" 
                      class="btn-gradient group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-white font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                  <i class="fas fa-sign-in-alt text-blue-200 group-hover:text-white"></i>
                </span>
                Sign in
              </button>
            </div>
          </form>
          
          <div class="mt-6">
            <div class="divider">New to FLC Clinic?</div>
            
            <div class="mt-4 text-center">
              <a href="{{ route('patient.register') }}" 
                 class="inline-flex items-center justify-center w-full py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-user-plus mr-2 text-blue-600"></i>
                Register
              </a>
            </div>
          </div>
        </div>
        
        <!-- Mobile Contact Info -->
        <div class="mt-6 md:hidden bg-blue-50 rounded-xl p-6">
          <h3 class="text-lg font-medium text-blue-800 mb-4 text-center">Contact Information</h3>
          <div class="grid grid-cols-1 gap-4">
            <div class="flex items-center">
              <div class="bg-blue-100 p-2 rounded-full mr-3">
                <i class="fas fa-phone text-blue-600"></i>
              </div>
              <p class="text-blue-800">(02) 123-4567</p>
            </div>
            <div class="flex items-center">
              <div class="bg-blue-100 p-2 rounded-full mr-3">
                <i class="fas fa-envelope text-blue-600"></i>
              </div>
              <p class="text-blue-800">flcclinic@example.com</p>
            </div>
            <div class="flex items-center">
              <div class="bg-blue-100 p-2 rounded-full mr-3">
                <i class="fas fa-map-marker-alt text-blue-600"></i>
              </div>
              <p class="text-blue-800">Bontoc, Mountain Province</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Simple Footer -->
  <footer class="bg-blue-800 text-white py-2 mt-3">
    <div class="container mx-auto px-6 text-center">
      <p class="text-sm">© {{ date('Y') }} FLC Clinic. All rights reserved.</p>
      <p class="text-blue-200 text-xs mt-2">Bontoc, Mountain Province • (02) 123-4567 • flcclinic@example.com</p>
    </div>
  </footer>
</body>
</html>