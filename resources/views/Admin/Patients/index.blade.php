@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}

</style>
<div class="container-fluid px-4 py-3">

  <!-- Page Title -->
  <div class="mb-4 text-center">
    <h2 class="fw-bold text-primary text-uppercase">Patients Records</h2>
    <hr class="border-2 border-primary w-25 mx-auto">
  </div>

  <!-- Search + Add -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Search -->
    <form action="#" method="GET" class="d-flex" style="max-width: 350px; width:100%;">
      <input type="text" 
             name="search" 
             class="form-control border-primary" 
             placeholder="Search patient..." 
             value="{{ request('search') }}">
      <button class="btn btn-primary ms-2 px-4">Search</button>
    </form>

    <!-- Add Patient -->
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary px-4">
      Add New Patient
    </a>
  </div>

  <!-- Patients Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0">
          <thead class="table-primary text-dark text-center">
            <tr>
              <th class="px-3">Full Name</th>
              <th>Address</th>
              <th>Sex</th>
              <th width="150">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($patients as $patient)
              <tr>
                <td class="px-3">
                    {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}
                </td>
                <td>{{ $patient->address ?? 'N/A' }}</td>

                <td>{{ ucfirst($patient->sex) }}</td>
                <td class="text-center">
                  <a href="{{ route('admin.records.index', $patient->id) }}"
                     class="btn btn-sm btn-outline-primary px-3">
                     View Records
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-3">
                  No patient records found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
