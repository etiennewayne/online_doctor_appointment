<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        $data = [
            [
                'schedule_id' => 2,
                'user_id' => 2,
                'appointment_date' => '2023-05-10',
                'status' => 0,
                'created_at' => '2023-05-10 08:06:30'
            ],
            [
                'schedule_id' => 2,
                'user_id' => 3,
                'appointment_date' => '2023-05-10',
                'status' => 0,
                'created_at' => '2023-05-10 09:06:30'
            ],
            [
                'schedule_id' => 2,
                'user_id' => 4,
                'appointment_date' => '2023-05-10',
                'status' => 0,
                'created_at' => '2023-05-10 10:06:30'
            ],
            [
                'schedule_id' => 2,
                'user_id' => 2,
                'appointment_date' => '2023-05-11',
                'status' => 2,
                'created_at' => '2023-05-11 10:06:30'
            ],

        ];

        \App\Models\Appointment::insertOrIgnore($data);

    }
}
