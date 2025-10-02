@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Registered Doctors</h2>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($doctors->isEmpty())
        <div class="alert alert-info">No Registered doctors at the moment.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Specialization</th>
                    <th>License Number</th>
                    <th>Registered</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->user->name ?? '—' }}</td>
                        <td>{{ $doctor->user->email ?? '—' }}</td>
                        <td>{{ $doctor->specialization ?? 'N/A' }}</td>
                         <td>{{ $doctor->license_number ?? '—' }}</td>
                        <td>{{ $doctor->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
