@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}

</style>
<div class="container my-4">
  <div class="card shadow-lg border-0 rounded-4">
    
    <!-- Header -->
    <div class="card-header bg-primary text-white text-center py-3 rounded-top">
      <h3 class="mb-0 fw-bold">New Patient Registration & Medical Record</h3>
      <small class="d-block">Hospital Consultation Record Form</small>
    </div>

    <div class="card-body p-4">
      <form id="multiStepForm" action="{{ route('admin.patients.storeManual') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- ==================== STEP 1: Patient Info & History ==================== -->
        <div id="section1">
          <h5 class="text-dark border-bottom pb-2 mb-4">Patient Identification Information</h5>

          <!-- User Account -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control shadow-sm" placeholder="Enter email" data-required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Password</label>
              <input type="password" name="password" class="form-control shadow-sm" placeholder="Enter password" data-required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control shadow-sm" placeholder="Confirm password" data-required>
            </div>
          </div>
        
          <!-- Patient Name -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">First Name</label>
              <input type="text" name="first_name" class="form-control shadow-sm" placeholder="Enter first name" data-required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Middle Name</label>
              <input type="text" name="middle_name" class="form-control shadow-sm" placeholder="Enter middle name">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Last Name</label>
              <input type="text" name="last_name" class="form-control shadow-sm" placeholder="Enter last name" data-required>
            </div>
          </div>

          <!-- Birthdate, Sex, Phone -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Birthdate</label>
              <input type="date" name="birthdate" class="form-control shadow-sm" data-required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Sex</label>
              <select name="sex" class="form-control shadow-sm" data-required>
                <option value="">Select sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Phone Number</label>
              <input type="text" name="phone_number" class="form-control shadow-sm" placeholder="e.g., 09123456789" data-required>
            </div>
          </div>

          <!-- Address -->
          <div class="mb-4">
            <label class="form-label fw-semibold">Address</label>
            <textarea name="address" class="form-control shadow-sm" rows="2" placeholder="Street, Barangay, City, Province" data-required></textarea>
          </div>

          <!-- Medical History -->
          <h5 class="text-dark border-bottom pb-2 mb-4">Medical History</h5>

          <div class="mb-3">
            <label class="form-label fw-semibold">Visit Date</label>
            <input type="date" name="visit_date" class="form-control shadow-sm" data-required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Reason of Visit</label>
            <input type="text" name="reason_of_visit" class="form-control shadow-sm" placeholder="Reason of visit" data-required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">History of Present Illness</label>
            <textarea name="history_present_illness" class="form-control shadow-sm" rows="2" placeholder="Describe current illness"></textarea>
          </div>

          <!-- Family Medical History -->
          <h4 class="fw-bold border-bottom pb-2">Family Medical History</h4>
          <div class="row g-3 mb-4">
            <div class="col-md-12">
              <label class="form-label d-block">Check if applicable:</label>
              @php
                  $familyHistory = ['hypertension','diabetes','heart_disease','cancer','tb'];
              @endphp
              @foreach($familyHistory as $fh)
              <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="family_{{ $fh }}" value="1">
                  <label class="form-check-label">{{ ucfirst(str_replace('_',' ',$fh)) }}</label>
              </div>
              @endforeach
            </div>
          </div>

          <!-- Immunization Records -->
          <h4 class="fw-bold border-bottom pb-2">Immunization Records</h4>
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Childhood Vaccinations</label>
              <textarea name="childhood_vaccines" rows="2" class="form-control shadow-sm" placeholder="Record childhood vaccinations"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Adult Vaccinations</label>
              <textarea name="adult_vaccines" rows="2" class="form-control shadow-sm" placeholder="Record adult vaccinations"></textarea>
            </div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <button type="button" class="btn btn-dark px-4" onclick="nextSection()">Next</button>
          </div>
        </div>

        <!-- ==================== STEP 2: Clinical Records & Vital Signs ==================== -->
        <div id="section2" style="display: none;">
          <!-- Clinical Records -->
          <h4 class="fw-bold border-bottom pb-2 mb-4">Clinical Records</h4>

          <div class="mb-3">
              <label class="form-label fw-semibold">Assessment</label>
              <textarea name="assessment" class="form-control shadow-sm" rows="2" placeholder="Doctor's assessment"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">Treatment Plan</label>
              <textarea name="treatment_plan" class="form-control shadow-sm" rows="2" placeholder="Planned treatment"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">Current Medications</label>
              <textarea name="current_medications" class="form-control shadow-sm" rows="2" placeholder="Medications patient is currently taking"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">Progress Notes</label>
              <textarea name="progress_notes" class="form-control shadow-sm" rows="2" placeholder="Progress notes"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">Diagnostic Results</label>
              <textarea name="diagnostic_results" class="form-control shadow-sm" rows="2" placeholder="Lab or imaging results"></textarea>
          </div>

          <!-- Vital Signs & Physical Exam -->
          <h5 class="text-dark border-bottom pb-2 mb-4">Vital Signs & Physical Examination</h5>
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Blood Pressure</label>
              <input type="text" name="bp" class="form-control shadow-sm" placeholder="e.g., 120/80">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Heart Rate</label>
              <input type="number" name="hr" class="form-control shadow-sm" placeholder="e.g., 72">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Respiratory Rate</label>
              <input type="number" name="rr" class="form-control shadow-sm" placeholder="e.g., 16">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Temperature</label>
              <input type="number" step="0.1" name="temp" class="form-control shadow-sm" placeholder="e.g., 36.5">
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Oxygen Saturation (%)</label>
              <input type="number" name="oxygen_saturation" class="form-control shadow-sm">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Weight (kg)</label>
              <input type="number" step="0.1" name="weight" class="form-control shadow-sm">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Height (cm)</label>
              <input type="number" step="0.1" name="height" class="form-control shadow-sm">
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary px-4" onclick="prevSection()">Back</button>
            <button type="submit" class="btn btn-success px-4">Save Patient & Record</button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
function nextSection() {
    // validate current step
    let valid = true;
    const currentFields = document.querySelectorAll('#section1 [data-required]');
    currentFields.forEach(el => {
        if (!el.value) {
            el.classList.add('is-invalid');
            valid = false;
        } else {
            el.classList.remove('is-invalid');
        }
    });

    if (!valid) return;

    // disable required on step 1
    currentFields.forEach(el => el.removeAttribute('required'));

    document.getElementById('section1').style.display = 'none';
    document.getElementById('section2').style.display = 'block';
}
document.getElementById('multiStepForm').addEventListener('submit', function(e) {
    const confirmed = confirm("Are you sure you want to save this patient and medical record?");
    if (!confirmed) {
        e.preventDefault(); // Stop form submission if user cancels
    }
});
function prevSection() {
    // restore required on step 1
    document.querySelectorAll('#section1 [data-required]').forEach(el => {
        el.setAttribute('required', 'required');
    });

    document.getElementById('section2').style.display = 'none';
    document.getElementById('section1').style.display = 'block';
}


</script>
@endsection
