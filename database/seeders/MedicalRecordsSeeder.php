<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Carbon\Carbon;

class MedicalRecordsSeeder extends Seeder
{
    public function run(): void
    {
        $patient = Patient::first(); // just seed for the first patient

        if ($patient) {
            MedicalRecord::create([
                'patient_id' => $patient->id,
                'name' => 'Prolactin',
                'note_type' => 'History and Physical',
                'author' => 'Dr. Branch',
                'date' => '2020-03-20',
                'last_updated' => '2020-04-28',
                'last_updated_by' => 'Stephanie Branch',
            ]);

            MedicalRecord::create([
                'patient_id' => $patient->id,
                'name' => 'Bilirubin, total',
                'note_type' => 'Cardiology consultation',
                'author' => 'Dr. Branch',
                'date' => '2020-03-29',
                'last_updated' => '2020-04-29',
                'last_updated_by' => 'Stephanie Branch',
            ]);
        }
    }
}
