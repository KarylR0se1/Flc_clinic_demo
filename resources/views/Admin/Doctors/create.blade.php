@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100" style="max-width: 700px;">
  <div class="card shadow-lg w-100">
    <div class="card-body p-4">
      <h2 class="mb-4 fw-bold text-primary text-center">
        <i class="bi bi-person-badge-fill me-2"></i>Doctor Registration
      </h2>

      {{-- Success message --}}
      @if(session('status'))
        <div id="status-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <script>
          setTimeout(() => {
            const alert = document.getElementById('status-alert');
            if (alert) bootstrap.Alert.getOrCreateInstance(alert).close();
          }, 5000);
        </script>
      @endif

      {{-- Error message --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf

        <!-- Step 1 -->
        <div id="modal-step-1" class="step active">
          <h5 class="fw-bold mb-3 text-black">Step 1: Account Information</h5>
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label">First Name</label>
              <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">

              <label class="form-label mt-2">Middle Name</label>
              <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">

              <label class="form-label mt-2">Last Name</label>
              <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">

              <label class="form-label mt-2">Email</label>
              <input type="email" name="email" class="form-control" required value="{{ old('email') }}">

              <label class="form-label mt-2">Password</label>
              <input type="password" name="password" id="password" class="form-control" required>
              <p id="password-feedback" class="small text-danger mt-1">
                Password must be at least 8 characters and include uppercase, lowercase, number, and special character.
              </p>

              <label class="form-label mt-2">Confirm Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
              <p id="password-match" class="small mt-1"></p>
            </div>
          </div>
        </div>

        <!-- Step 2 -->
        <div id="modal-step-2" class="step">
          <h5 class="fw-bold mb-3 text-black">Step 2: Professional Information</h5>
          <div class="mb-3">
            <label class="form-label">Specialization</label>
            <input type="text" name="specialization" class="form-control" required value="{{ old('specialization') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">License Number</label>
            <input type="text" name="license_number" class="form-control" required value="{{ old('license_number') }}">
          </div>
        </div>

        <!-- Step 3 -->
        <div id="modal-step-3" class="step">
          <h5 class="fw-bold mb-3 text-black">Step 3: Schedule Availability</h5>
          <div id="schedule-wrapper">
            <div class="schedule-item row g-3 mb-3">
              <div class="col-md-4">
                <label class="form-label">Day</label>
                <select name="schedule[0][day]" class="form-select" required>
                  <option value="">-- Select Day --</option>
                  @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">Start Time</label>
                <input type="time" name="schedule[0][start]" class="form-control" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">End Time</label>
                <input type="time" name="schedule[0][end]" class="form-control" required>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-outline-primary btn-sm" onclick="addScheduleRow()">
            <i class="bi bi-plus-circle"></i> Add Another Schedule
          </button>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between mt-4">
          <button type="button" id="prev-btn" class="btn btn-secondary d-none" onclick="prevStep()">Back</button>
          <div>
            <button type="button" id="next-btn" class="btn btn-primary" onclick="nextStep()">Next</button>
            <button type="submit" id="submit-btn" class="btn btn-success d-none">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
  .step { display: none; opacity: 0; transition: opacity 0.4s ease-in-out; }
  .step.active { display: block; opacity: 1; }
  .card { border-radius: 12px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector("form");
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

  function validatePassword() {
    const val = passwordInput.value;
    const valid =
      rules.length.test(val) &&
      rules.upper.test(val) &&
      rules.lower.test(val) &&
      rules.number.test(val) &&
      rules.special.test(val);

    if (!val) {
      feedback.textContent = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
      feedback.className = "small text-danger mt-1";
    } else if (valid) {
      feedback.textContent = "✅ Strong password";
      feedback.className = "small text-success mt-1";
    } else {
      feedback.textContent = "❌ Password does not meet requirements.";
      feedback.className = "small text-danger mt-1";
    }
  }

  function checkPasswordMatch() {
    if (!confirmInput.value) {
      passwordMatch.textContent = "";
    } else if (confirmInput.value === passwordInput.value) {
      passwordMatch.textContent = "✅ Passwords match";
      passwordMatch.className = "small text-success mt-1";
    } else {
      passwordMatch.textContent = "❌ Passwords do not match";
      passwordMatch.className = "small text-danger mt-1";
    }
  }

  passwordInput.addEventListener('input', validatePassword);
  confirmInput.addEventListener('input', checkPasswordMatch);

  form.addEventListener('submit', (e) => {
    const val = passwordInput.value;
    const confirmVal = confirmInput.value;
    const valid =
      rules.length.test(val) &&
      rules.upper.test(val) &&
      rules.lower.test(val) &&
      rules.number.test(val) &&
      rules.special.test(val);

    if (!valid || val !== confirmVal) {
      e.preventDefault();
      alert("Please fix password errors before proceeding.");
    }
  });
});

let currentStep = 1;
function showStep(step) {
  document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
  document.getElementById('modal-step-' + step).classList.add('active');
  document.getElementById('prev-btn').classList.toggle('d-none', step === 1);
  document.getElementById('next-btn').classList.toggle('d-none', step === 3);
  document.getElementById('submit-btn').classList.toggle('d-none', step !== 3);
}
function nextStep() { if (currentStep < 3) { currentStep++; showStep(currentStep); } }
function prevStep() { if (currentStep > 1) { currentStep--; showStep(currentStep); } }
function addScheduleRow() {
  const index = document.querySelectorAll('.schedule-item').length;
  const html = `
    <div class="schedule-item row g-3 mb-3">
      <div class="col-md-4">
        <select name="schedule[${index}][day]" class="form-select" required>
          <option value="">-- Select Day --</option>
          @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
            <option value="{{ $day }}">{{ $day }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4"><input type="time" name="schedule[${index}][start]" class="form-control" required></div>
      <div class="col-md-4"><input type="time" name="schedule[${index}][end]" class="form-control" required></div>
    </div>`;
  document.getElementById('schedule-wrapper').insertAdjacentHTML('beforeend', html);
}
document.getElementById('submit-btn').addEventListener('click', function(e) {
    e.preventDefault(); // prevent immediate submission

    const confirmation = confirm("Are you sure you want to register this doctor?");
    if (confirmation) {
        // If confirmed, submit the form
        this.closest('form').submit();
    }
});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
