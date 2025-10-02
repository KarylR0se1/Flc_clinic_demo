<!DOCTYPE html>
<html>
<head>
    <title>Medical Record Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-size: 14px; 
            font-family: "Times New Roman", serif; 
            color: #000;
        }
        
        .print-container { 
            margin: 40px 60px; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
        }
        .header img { 
            height: 80px; 
            margin-bottom: 10px; 
        }
        .header h2 { 
            margin: 0; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 20px;
        }
        .header p { 
            margin: 0; 
            font-size: 12px; 
        }
        hr {
            border: 1px solid #000;
            margin: 15px 0 25px;
        }
        .section-title { 
            font-weight: bold; 
            text-decoration: underline;
            margin-top: 20px; 
            margin-bottom: 10px;
            font-size: 15px;
        }
        .content p { 
            margin: 2px 0; 
        }
        .signature { 
            margin-top: 60px; 
            text-align: right; 
        }
        .signature-line { 
            border-top: 1px solid #000; 
            width: 250px; 
            margin-left: auto; 
            margin-bottom: 5px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">

        <!-- Header -->
        <div class="header">
            <h2>FLC Specialty Clinic</h2>
            <p>Bontoc Mountain Province Cordillera Administrative Region Philippines 2620</p>
            <p>Contact: (02) 123-4567 | Email: flcclinic@example.com</p>
        </div>

        <hr>

        <!-- Patient & Record Info -->
        <div class="content">
            <p><strong>Patient:</strong> {{ $record->patient->first_name }} {{ $record->patient->last_name }}</p>
            <p><strong>Doctor:</strong> {{ $record->doctor->full_name ?? 'Not Assigned' }}</p>
            <p><strong>Date of Visit:</strong> 
                @if($record->visit_date)
                    {{ \Carbon\Carbon::parse($record->visit_date)->format('F d, Y') }}
                @else
                    N/A
                @endif
            </p>
        </div>
         <!-- Vital Signs -->
        <h5 class="section-title">Vital Signs</h5>
        <p><strong>Blood Pressure:</strong> {{ $record->bp ?? 'N/A' }}</p>
        <p><strong>Heart Rate:</strong> {{ $record->hr ?? 'N/A' }} bpm</p>
        <p><strong>Respiratory Rate:</strong> {{ $record->rr ?? 'N/A' }} breaths/min</p>
        <p><strong>Temperature:</strong> {{ $record->temp ?? 'N/A' }} °C</p>
        <p><strong>Oxygen Saturation:</strong> {{ $record->oxygen_saturation ?? 'N/A' }} %</p>
        <p><strong>Weight:</strong> {{ $record->weight ?? 'N/A' }} kg</p>
        <p><strong>Height:</strong> {{ $record->height ?? 'N/A' }} cm</p>
        
        <!-- Chief Complaint -->
        <h5 class="section-title">Reason of Visit</h5>
        <p>{{ $record->reason_of_visit ?? 'N/A' }}</p>

        <!-- Medications -->

<h5 class="section-title">Medications</h5>
<p>{{ $record->current_medication ?? 'No medications recorded' }}</p>


        
        <!-- Doctor's Signature -->
        <div class="signature">
            <p class="signature-line"></p>
            <p><strong>Dr. {{ $record->doctor->full_name ?? '________________' }}</strong></p>
            <p><em>Attending Physician</em></p>
        </div>
    </div>
</body>
</html>
