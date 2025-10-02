<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ config('app.name', 'FLC Clinic') }} - Patient Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

  <!-- Registration Form -->
  <div class="w-full max-w-md bg-white p-6 rounded shadow mt-20 mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Patient Registration</h2>

    <form id="registrationForm" action="{{ route('register.patient') }}" method="POST">
        @csrf

        <!-- Step 1: Account Information -->
        <div id="step1" class="step active">
            <h3 class="font-semibold mb-4 text-gray-700">Step 1: Account Information</h3>

            <input name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required class="w-full mb-3 p-2 border rounded">
            <input name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name" class="w-full mb-3 p-2 border rounded">
            <input name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required class="w-full mb-3 p-2 border rounded">
            <input name="email" value="{{ old('email') }}" placeholder="Email" type="email" required class="w-full mb-3 p-2 border rounded">

            <p class="text-red-600 text-sm mb-3">
                Password must be at least 8 characters, include at least one uppercase letter, one lowercase letter, one number, and one special character (!@#$%^&*).
            </p>
            <input id="password" name="password" placeholder="Password" type="password" required class="w-full mb-1 p-2 border rounded">
            <div id="password-feedback" class="text-sm mb-2 text-gray-600"></div>

            <input id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" type="password" required class="w-full mb-3 p-2 border rounded">
            <div id="password-match" class="text-sm mb-2 text-gray-600"></div>

            <button type="button" id="nextBtn" class="w-full p-2 bg-blue-600 hover:bg-blue-700 text-white rounded mt-2">Next</button>
        </div>

        <!-- Step 2: Personal Information -->
        <div id="step2" class="step hidden">
            <h3 class="font-semibold mb-4 text-gray-700">Step 2: Personal Information</h3>

            <label class="block mb-1 font-medium">Sex</label>
            <select name="sex" required class="w-full mb-3 p-2 border rounded">
                <option value="" disabled selected>Select Sex</option>
                <option value="male" {{ old('sex')=='male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('sex')=='female' ? 'selected' : '' }}>Female</option>
            </select>

            <label class="block mb-1 font-medium">Birth Date</label>
            <input name="birthdate" type="date" required class="w-full mb-3 p-2 border rounded" value="{{ old('birthdate') }}">

            <input name="address" type="text" placeholder="Address" required class="w-full mb-3 p-2 border rounded" value="{{ old('address') }}">

            <!-- Terms and Conditions -->
            <label class="inline-flex items-center space-x-2 mt-2">
                <input type="checkbox" name="terms" required class="form-checkbox" {{ old('terms') ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">
                    I agree to the <a href="{{ route('terms') }}" class="text-blue-600 underline">Terms and Conditions</a>
                </span>
            </label>

            <div class="flex justify-between mt-4">
                <button type="button" id="backBtn" class="p-2 bg-gray-500 hover:bg-gray-600 text-white rounded">Back</button>
                <button type="submit" class="p-2 bg-green-600 hover:bg-green-700 text-white rounded">Register</button>
            </div>
        </div>

    </form>

    <p class="mt-6 text-center text-sm text-gray-700">
      Already have an account?
      <a href="{{ route('home') }}" class="text-blue-600 font-semibold hover:underline">
        Login
      </a>
    </p>
  </div>

  <style>
    body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
  </style>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('registrationForm');
      const passwordInput = document.getElementById('password');
      const confirmInput = document.getElementById('password_confirmation');
      const feedback = document.getElementById('password-feedback');
      const passwordMatch = document.getElementById('password-match');

      const rules = {
          length: /.{8,}/,
          upper: /[A-Z]/,
          lower: /[a-z]/,
          number: /\d/,
          special: /[!@#$%^&*]/,
      };

      // Password validation
      function validatePassword() {
          const val = passwordInput.value;
          const valid = rules.length.test(val) &&
                        rules.upper.test(val) &&
                        rules.lower.test(val) &&
                        rules.number.test(val) &&
                        rules.special.test(val);

          if (!val) {
              feedback.textContent = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
              feedback.className = "small text-red-600 mt-1";
          } else if (valid) {
              feedback.textContent = "Strong password";
              feedback.className = "small text-green-600 mt-1";
          } else {
              feedback.textContent = "Password does not meet requirements.";
              feedback.className = "small text-red-600 mt-1";
          }
      }

      function checkPasswordMatch() {
          if (!confirmInput.value) {
              passwordMatch.textContent = "";
          } else if (confirmInput.value === passwordInput.value) {
              passwordMatch.textContent = "Passwords match";
              passwordMatch.className = "small text-green-600 mt-1";
          } else {
              passwordMatch.textContent = "Passwords do not match";
              passwordMatch.className = "small text-red-600 mt-1";
          }
      }

      passwordInput.addEventListener('input', () => {
          validatePassword();
          saveFormData();
      });
      confirmInput.addEventListener('input', checkPasswordMatch);

      // Multi-step navigation
      const nextBtn = document.getElementById('nextBtn');
      const backBtn = document.getElementById('backBtn');
      const step1 = document.getElementById('step1');
      const step2 = document.getElementById('step2');

      nextBtn.addEventListener('click', () => {
          step1.classList.add('hidden');
          step2.classList.remove('hidden');
          saveFormData();
      });

      backBtn.addEventListener('click', () => {
          step2.classList.add('hidden');
          step1.classList.remove('hidden');
          saveFormData();
      });

      // Save form inputs to localStorage
      function saveFormData() {
          const inputs = form.querySelectorAll('input, select');
          inputs.forEach(input => {
              if (['_token','password','password_confirmation'].includes(input.name)) return; // skip CSRF & passwords
              if(input.type === 'checkbox') {
                  localStorage.setItem(input.name, input.checked);
              } else {
                  localStorage.setItem(input.name, input.value);
              }
          });
      }

      // Load saved inputs from localStorage
      function loadFormData() {
          const inputs = form.querySelectorAll('input, select');
          inputs.forEach(input => {
              if (['_token','password','password_confirmation'].includes(input.name)) return; // skip CSRF & passwords
              if(localStorage.getItem(input.name) !== null) {
                  if(input.type === 'checkbox') {
                      input.checked = (localStorage.getItem(input.name) === 'true');
                  } else {
                      input.value = localStorage.getItem(input.name);
                  }
              }
          });
      }

      // Clear localStorage after successful submission with confirmation
      form.addEventListener('submit', (e) => {
          const confirmed = confirm("Are you sure you want to register with these details?");
          if (!confirmed) {
              e.preventDefault(); // stop form submission
              return false;
          }
          localStorage.clear();
      });

      // Initialize form with saved data
      loadFormData();
  });
  </script>

</body>
</html>
