<?php

use Illuminate\Database\Seeder;
use App\Ticket;

class TicketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ticket::truncate();

        $faker = \Faker\Factory::create();
        $priority = [];
        for ($i = 0; $i < 20; $i ++) {
            $random_status = rand(1,3);
            $priority[$random_status] = (isset($priority[$random_status])) ? ($priority[$random_status] + 1) : 1;
            Ticket::create([
                'title' => $faker->sentence,
                'description' => $faker->sentence,
                'user_id' => 1,
                'status' => $random_status,
                'priority' => $priority[$random_status]
            ]);
        }
    }
}
