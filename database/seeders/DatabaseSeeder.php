<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterCustomer;
use App\Models\NameList;
use App\Models\User;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $faker->seed(13);

        // for ($i=0; $i < 5000; $i++) {
        //     MasterCustomer::create(
        //         [
        //             'cust_type' => 'R',
        //             // 'name' => $faker->firstName." ".$faker->lastName,
        //             'name' => $faker->unique()->name,
        //             'country_code' => 'ID',
        //             'birthplace' => $faker->city,
        //             'birthdate' => $faker->date($format = 'd/m/Y', $max = 'now'),
        //             'current_address_type' => 'K',
        //             'current_address' => $faker->address,
        //             'city' => $faker->city,
        //             'current_country_code' => $faker->countryCode,
        //             'zip_code' => $faker->postcode,
        //             'contact_type' => 'D',
        //             'communication_type' => 'MOB',
        //             'country_prefix' => '62',
        //             'phone_number' => '081271484136',
        //             'cif' => '49525751',
        //             'account_type' => 'TPE',
        //             'account_status' => 'AKT',
        //             'account_num' => '770045621254',
        //             'pjk_id' => 'asdas-asdasd-asdsad-asd',
        //             'card_num' => '0234456214521546',
        //             'id_type' => 'KTP',
        //             'id_num' => $faker->nik()
        //         ]
        //     );

        //     MasterCustomer::create(
        //         [
        //             'cust_type' => 'C',
        //             'name' => $faker->unique()->company,
        //             'country_code' => 'ID',
        //             'birthplace' => $faker->city,
        //             'birthdate' => $faker->date($format = 'd/m/Y', $max = 'now'),
        //             'current_address_type' => 'K',
        //             'current_address' => $faker->address,
        //             'city' => $faker->city,
        //             'current_country_code' => $faker->countryCode,
        //             'zip_code' => $faker->postcode,
        //             'contact_type' => 'D',
        //             'communication_type' => 'MOB',
        //             'country_prefix' => '62',
        //             'phone_number' => '081271484136',
        //             'cif' => '49525751',
        //             'account_type' => 'TPE',
        //             'account_status' => 'AKT',
        //             'account_num' => '770045621254',
        //             'pjk_id' => 'asdas-asdasd-asdsad-asd',
        //             'card_num' => '0234456214521546',
        //             'id_type' => 'KTP',
        //             'id_num' => $faker->nik()
        //         ]
        //     );

        //     NameList::create(
        //         [
        //             'name' => $faker->unique()->name,
        //             'id_num' => $faker->nik(),
        //             'birthplace' => $faker->city,
        //             'birthdate' => $faker->date($format = 'd/m/Y', $max = 'now'),
        //         ]
        //     );

        //     NameList::create(
        //         [
        //             'name' => $faker->unique()->company,
        //             'id_num' => $faker->nik(),
        //             'birthplace' => $faker->city,
        //             'birthdate' => $faker->date($format = 'd/m/Y', $max = 'now'),
        //         ]
        //     );
        // }

        User::create(
            [
                'name' => 'shinhan_adm',
                'email' => '',
                'username' => 'shinhan_adm',
                'password' => bcrypt('shinhan@406'),
                'role' => ''
            ]
        );
        User::create(
            [
                'name' => 'dicky',
                'email' => '',
                'username' => 'dicky',
                'password' => bcrypt('shinhan@1'),
                'role' => ''
            ]
        );
        User::create(
            [
                'name' => 'william',
                'email' => '',
                'username' => 'william',
                'password' => bcrypt('shinhan@1'),
                'role' => ''
            ]
        );
        User::create(
            [
                'name' => 'andika',
                'email' => '',
                'username' => 'andika',
                'password' => bcrypt('shinhan@1'),
                'role' => ''
            ]
        );
    }
}
