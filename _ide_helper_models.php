<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAdmin {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Allergy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Allergy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Allergy query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAllergy {}
}

namespace App\Models{
/**
 * @property \App\Models\User $user
 * @property int $id
 * @property int $patient_id
 * @property int $doctor_id
 * @property \Illuminate\Support\Carbon $appointment_date
 * @property \Illuminate\Support\Carbon $appointment_time
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor $doctor
 * @property-read mixed $patient_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LaboratoryRequest> $laboratoryRequests
 * @property-read int|null $laboratory_requests_count
 * @property-read \App\Models\MedicalRecord|null $medicalRecord
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\DoctorSchedule|null $schedule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAppointmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAppointmentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAppointment {}
}

namespace App\Models{
/**
 * App\Models\Doctor
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $specialization
 * @property string $license_number
 * @property string|null $profile_picture
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DoctorSchedule[] $schedules
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read string $full_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $appointments_count
 * @property-read int|null $schedules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDoctor {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $doctor_id
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor $doctor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDoctorSchedule {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Immunization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Immunization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Immunization query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperImmunization {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $medical_record_id
 * @property int $patient_id
 * @property int|null $appointment_id
 * @property string|null $patient_name
 * @property string|null $test_type
 * @property string|null $requested_by
 * @property string|null $request_date
 * @property string|null $result_file
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Appointment|null $appointment
 * @property-read \App\Models\MedicalRecord $medicalRecord
 * @property-read \App\Models\Patient $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereMedicalRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest wherePatientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereRequestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereRequestedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereResultFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereTestType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaboratoryRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLaboratoryRequest {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $patient_id
 * @property int|null $appointment_id
 * @property int|null $doctor_id
 * @property string|null $visit_date
 * @property string|null $reason_of_visit
 * @property string|null $history_of_present_illness
 * @property string|null $examination
 * @property string|null $assessment
 * @property string|null $treatment_plan
 * @property string|null $current_medications
 * @property string|null $progress_notes
 * @property string|null $diagnostic_results
 * @property string|null $bp
 * @property string|null $hr
 * @property string|null $rr
 * @property string|null $temp
 * @property string|null $oxygen_saturation
 * @property string|null $weight
 * @property string|null $height
 * @property string|null $bmi
 * @property string|null $physical_exam
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $family_hypertension
 * @property int $family_diabetes
 * @property int $family_heart_disease
 * @property int $family_cancer
 * @property int $family_tb
 * @property string|null $childhood_vaccines
 * @property string|null $adult_vaccines
 * @property string|null $past_surgeries
 * @property string|null $pre_conditions
 * @property string|null $medication_compliance
 * @property string|null $treatment_history
 * @property string|null $lab_results
 * @property-read \App\Models\Appointment|null $appointment
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LaboratoryRequest> $laboratoryRequests
 * @property-read int|null $laboratory_requests_count
 * @property-read \App\Models\Patient $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereAdultVaccines($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereBmi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereBp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereChildhoodVaccines($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereCurrentMedications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereDiagnosticResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereExamination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereFamilyCancer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereFamilyDiabetes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereFamilyHeartDisease($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereFamilyHypertension($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereFamilyTb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereHistoryOfPresentIllness($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereHr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereLabResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereMedicationCompliance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereOxygenSaturation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord wherePastSurgeries($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord wherePhysicalExam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord wherePreConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereProgressNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereReasonOfVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereRr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereTreatmentHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereTreatmentPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereVisitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalRecord whereWeight($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMedicalRecord {}
}

namespace App\Models{
/**
 * @property-read \App\Models\MedicalRecord|null $medicalRecord
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMedication {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $data
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperNotification {}
}

namespace App\Models{
/**
 * App\Models\Patient
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property \Carbon\Carbon $birthdate
 * @property string $sex
 * @property string $address
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MedicalRecord[] $medicalRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Allergy[] $allergies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Immunization[] $immunizations
 * @property-read string $full_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $appointments_count
 * @property-read int|null $medical_records_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPatient {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property \Carbon\Carbon|null $email_verified_at
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\Admin|null $admin
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $appointments_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

