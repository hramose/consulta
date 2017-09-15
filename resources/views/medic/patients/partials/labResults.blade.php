
<lab-exams :exams="{{ $patient->labexams->load('results') }}" :patient_id="{{ $patient->id }}" :read="true"></lab-exams>