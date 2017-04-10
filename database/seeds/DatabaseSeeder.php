<?php

use App\Role;
use App\Speciality;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    private $tables = [
        'users','offices','roles','role_user', 'specialities'
    ];
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $specialities = [
    "Acupunturista","Adicciones","Alergólogo","Algólogo","Anestesiólogo","Angiólogo","Audiólogo y Foniatra","Bariatra","Biotecnología","Cardiólogo","Cardiólogo Hemodinamista","Cardiólogo Pediatra","Cirugía Maxilofacial","Cirujano Cardiotorácico","Cirujano Endocrinólogo","Cirujano Gastroenterólogo","Cirujano General","Cirujano Oncólogo","Cirujano Pediatra","Cirujano Plástico","Cirujano Vascular","Coloproctólogo","Dentista","Dermatólogo","Dermatólogo Pediatra","Dermocosmiatra","Endocrinólogo","Endocrinólogo Pediatra","Endodoncista","Endoperiodontólogo","Estomatología","Fisioterapeuta","Gastroenterólogo","Gastroenterólogo Pediatra","Genetista","Geriatra","Ginecología Oncológica","Ginecólogo y Obstetra","Hematólogo","Hematólogo Pediatra","Homeópata","Infectólogo","Infectólogo Pediatra","Inmunólogo","Internista","Maxilofacial","Medicina del Trabajo","Médico Alternativo","Médico de Rehabilitación","Médico Deportivo","Médico en Biología de la Reproducción","Médico Estético","Médico General","Médico Intensivista","Nefrólogo","Nefrólogo (Hemodiálisis)","Nefrólogo Pediatra","Neonatólogo","Neumólogo","Neumólogo Pediatra","Neurocirujano","Neurólogo","Neurólogo Pediatra","Neuroradiólogo","Nutriólogo","Nutriólogo Clínico","Odontopediatra","Oftalmólogo","Oftalmólogo Pediatra","Oncólogo","Oncólogo Pediatra","Optometrista","Ortodoncista","Ortopedista Pediatra","Ortopedista y Traumatólogo","Otorrinolaringólogo","Otorrinolaringólogo Pediatra","Patólogo Bucal","Pediatra","Periodoncista","Podiatra","Prostodoncista","Psicólogo","Psiquiatra","Quiropráctico","Radiólogo","Rehabilitación en Lenguaje y Audición","Reumatólogo","Urgencias Médico Quirúrgicas","Urólogo","Urólogo Pediatra","Odontología","Cirujía","Ginecología","Médico Familiar"
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

        foreach ($this->specialities as $s) {
            factory(Speciality::class, 1)->create([
                'name' => $s,
            ]);
        }

        factory(User::class, 1)->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
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
