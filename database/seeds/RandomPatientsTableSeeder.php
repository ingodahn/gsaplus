<?php

use App\Patient;

use App\Models\PatientStatus;

use App\Helper;

class RandomPatientsTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = $this->faker;

        foreach (array_keys(PatientStatus::$STATUS_INFO) as $patient_status) {
            if ($patient_status === PatientStatus::UNKNOWN ||
                $patient_status === PatientStatus::COLLABORATION_ENDED) {
                continue;
            }

            $patients = factory(Patient::class, 5)
                ->make()
                ->each(function (Patient $p) use ($faker) {
                    Helper::set_developer_attributes($p);

                    $p->personal_information = $faker->realText();
                    $p->notes_of_therapist = $faker->realText();

                    $p->save();
                });;

            foreach ($patients as $patient) {
                $this->fill_in_assignments($patient, $patient_status);
            }
        }
    }
}
