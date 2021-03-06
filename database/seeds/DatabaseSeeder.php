<?php

use App\Configuration;
use App\Role;
use App\Speciality;
use App\User;
use Illuminate\Database\Seeder;
use App\Plan;

class DatabaseSeeder extends Seeder
{
    private $tables = [
        'users', 'offices', 'roles', 'role_user', 'specialities', 'speciality_user', 'verified_offices', 'office_user', 'patients', 'patient_user', 'settings', 'histories', 'invoices', 'invoice_services', 'invoice_lines', 'review_services', 'review_medics', 'balances', 'assistants_users', 'assistants_offices', 'pressures', 'sugars', 'allergies', 'appointments', 'diagnostics', 'disease_notes', 'ginecos', 'heredos', 'medicines', 'nopathologicals', 'pathologicals', 'physical_exams', 'reminders', 'schedules', 'treatments', 'vital_signs', 'incomes', 'configurations', 'subscriptions', 'plans', 'reset_codes', 'config_facturas', 'hacienda_notifications', 'documento_referencias', 'app_notifications','pharmacies','pharmacy_user','assistants_pharmacies'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $specialities = [
     'Acupunturista', 'Adicciones', 'Alergólogo', 'Algólogo', 'Anestesiólogo', 'Angiólogo', 'Audiólogo y Foniatra', 'Bariatra', 'Biotecnología', 'Cardiólogo', 'Cardiólogo Hemodinamista', 'Cardiólogo Pediatra', 'Cirugía Maxilofacial', 'Cirujano Cardiotorácico', 'Cirujano Endocrinólogo', 'Cirujano Gastroenterólogo', 'Cirujano General', 'Cirujano Oncólogo', 'Cirujano Pediatra', 'Cirujano Plástico', 'Cirujano Vascular', 'Coloproctólogo', 'Dentista', 'Dermatólogo', 'Dermatólogo Pediatra', 'Dermocosmiatra', 'Endocrinólogo', 'Endocrinólogo Pediatra', 'Endodoncista', 'Endoperiodontólogo', 'Estomatología', 'Fisioterapeuta', 'Gastroenterólogo', 'Gastroenterólogo Pediatra', 'Genetista', 'Geriatra', 'Ginecología Oncológica', 'Ginecólogo y Obstetra', 'Hematólogo', 'Hematólogo Pediatra', 'Homeópata', 'Infectólogo', 'Infectólogo Pediatra', 'Inmunólogo', 'Internista', 'Maxilofacial', 'Medicina del Trabajo', 'Médico Alternativo', 'Médico de Rehabilitación', 'Médico Deportivo', 'Médico en Biología de la Reproducción', 'Médico Estético', 'Médico Intensivista', 'Nefrólogo', 'Nefrólogo (Hemodiálisis)', 'Nefrólogo Pediatra', 'Neonatólogo', 'Neumólogo', 'Neumólogo Pediatra', 'Neurocirujano', 'Neurólogo', 'Neurólogo Pediatra', 'Neuroradiólogo', 'Nutriólogo', 'Nutriólogo Clínico', 'Odontopediatra', 'Oftalmólogo', 'Oftalmólogo Pediatra', 'Oncólogo', 'Oncólogo Pediatra', 'Optometrista', 'Ortodoncista', 'Ortopedista Pediatra', 'Ortopedista y Traumatólogo', 'Otorrinolaringólogo', 'Otorrinolaringólogo Pediatra', 'Patólogo Bucal', 'Pediatra', 'Periodoncista', 'Podiatra', 'Prostodoncista', 'Psicólogo', 'Psiquiatra', 'Quiropráctico', 'Radiólogo', 'Rehabilitación en Lenguaje y Audición', 'Reumatólogo', 'Urgencias Médico Quirúrgicas', 'Urólogo', 'Urólogo Pediatra', 'Odontología', 'Cirujía', 'Ginecología', 'Médico Familiar'
    ];

    public function run()
    {
        $this->cleanDatabase();

        factory(Role::class, 1)->create();
        factory(Role::class, 1)->create([
            'name' => 'paciente',
        ]);
        factory(Role::class, 1)->create([
            'name' => 'administrador',
        ]);
        factory(Role::class, 1)->create([
            'name' => 'clinica',
        ]);
        factory(Role::class, 1)->create([
            'name' => 'asistente',
        ]);
        factory(Role::class, 1)->create([
            'name' => 'farmacia',
        ]);

        foreach ($this->specialities as $s) {
            factory(Speciality::class, 1)->create([
                'name' => $s,
            ]);
        }

        $admin1 = factory(User::class, 1)->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'provider' => 'email',
            'provider_id' => 'admin@admin.com',
            'api_token' => str_random(50),
            'remember_token' => str_random(10),
        ]);
        $admin2 = factory(User::class, 1)->create([
            'name' => 'admin Julio',
            'email' => 'info@gpsmedica.com',
            'password' => bcrypt('123456'),
            'provider' => 'email',
            'provider_id' => 'info@gpsmedica.com',
            'api_token' => str_random(50),
            'remember_token' => str_random(10),
        ]);
        \DB::table('role_user')->insert(
    ['role_id' => 3, 'user_id' => 1]
);
        \DB::table('role_user')->insert(
    ['role_id' => 3, 'user_id' => 2]
);

        $confi = factory(Configuration::class, 1)->create();

        $plan1 = factory(Plan::class, 1)->create();
        $plan2 = factory(Plan::class, 1)->create([
               'title' => 'Plan $30',
                'cost' => 30,
                'quantity' => 3
          ]);
        $plan3 = factory(Plan::class, 1)->create([
               'title' => 'Plan $50',
                'cost' => 50,
                'quantity' => 6
           ]);
        $plan4 = factory(Plan::class, 1)->create([
                'title' => 'Plan $100',
                'cost' => 100,
                'quantity' => 12
            ]);
    }

    private function cleanDatabase()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($this->tables as $tablename) {
            DB::table($tablename)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
