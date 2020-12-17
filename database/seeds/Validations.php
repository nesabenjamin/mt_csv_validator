<?php

use Illuminate\Database\Seeder;

class Validations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['name' => 'required'],
            ['name' => 'symbol'],
            ['name' => 'email']         
        ];
        DB::table('validations')->insert($data);      
    }
}
