@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}

</style>
<div class="container">
    <h2>Medical Record Details</h2>

    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($record->date)->format('F d, Y') }}</p>
    <p><strong>Type of Service:</strong> {{ $record->type_of_service }}</p>
    <p><strong>Doctor:</strong> {{ $record->doctor_name }}</p>
    <p><strong>Notes:</strong> {{ $record->notes ?? 'No additional notes.' }}</p>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
</div>
@endsection
