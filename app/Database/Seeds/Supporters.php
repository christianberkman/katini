<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Supporters extends Seeder
{
    public function run()
    {
        $number     = 10;
        $supporters = [];

        for ($i = 1; $i <= $number; $i++) {
            $this->db->table('supporters')->insert($this->generateSupporterData());
        }
    }

    private function generateSupporterData()
    {
        $data = [];

        /**
         * Names
         */
        // Name data
        $firstNames = ['Christian', 'Katie', 'Patricia', 'Elliot', 'Aat', 'Janneke', 'Steve', 'Lynn', 'C', 'A.P.H.', 'Miranda'];
        $lastNames  = ['Berkman', 'Schinnell', 'Alblas', 'Buitendijk', 'Hooft', 'Berg'];
        $infixes    = ['van der', 'v.d.', 'van \'t', 'de'];

        // First Name
        $firstNameRnd = mt_rand(1, 100);
        if ($firstNameRnd < 80) {
            $data['first_name'] = $this->randomValue($firstNames);
        } else {
            $data['first_name'] = null;
        }

        // Last Name
        $lastNameRnd = mt_rand(1, 100);
        if ($lastNameRnd < 60) {
            $data['last_name'] = $this->randomValue($lastNames);
        } elseif ($lastNameRnd < 80) {
            $data['last_name'] = $this->randomValue($lastNames);
            $data['infix']     = $this->randomValue($infixes);
        } else {
            $data['last_name'] = null;
            $data['infix']     = null;
        }

        // No name
        if (empty($data['fist_name']) && empty($data['last_name'])) {
            $data['first_name'] = $this->randomValue($firstNames);
        }

        /**
         * Org
         */
        $orgNames = ['CodeIgniter B.V.', 'Leekeerd B.V.', 'ACME Inc.'];

        $orgRnd = mt_rand(1, 100);
        if ($orgRnd < 10) {
            $data['org_name'] = $this->randomValue($orgNames);
        } else {
            $data['org_name'] = null;
        }

        /**
         * Title
         */
        $titles = ['Dhr.', 'Mevr.', 'Fam.'];

        $titleRnd = mt_rand(1, 100);
        if ($titleRnd < 50) {
            $data['title'] = $this->randomValue($titles);
        } else {
            $data['title'] = null;
        }

        /**
         * Email
         */
        $emailRnd = mt_rand(1, 100);
        if ($emailRnd < 90) {
            $data['email'] = strtolower(($data['first_name'] ?? $data['last_name']) . '@domain.local');
        }

        /**
         * Phone
         */
        $phoneRnd = mt_rand(1, 100);
        if ($phoneRnd < 70) {
            $data['phone'] = mt_rand(31111111111, 319911111111);
        } else {
            $data['phone'] = null;
        }

        /**
         * Address
         */
        $streets   = ['Oosteinde', 'Leekeerd', 'Stationsstraat', 'Lange Laan'];
        $postcodes = ['SV', 'AB', 'DE', 'XY'];
        $cities    = ['Delft', 'Rotterdam', 'Amsterdam', 'Zoetermeer', 'Den Haag', 'Veenendaal', 'Hendrik-Ido-Ambacht'];

        $addressRnd = mt_rand(1, 100);
        if ($addressRnd < 70) {
            $data['address_street']   = $this->randomValue($streets);
            $data['address_number']   = mt_rand(1, 999);
            $data['address_postcode'] = mt_rand(1111, 9999) . $this->randomValue($postcodes);
            $data['address_city']     = $this->randomValue($cities);
        }

        $data['created_at'] = time();
        $data['updated_by'] = 1;

        /**
         * Return
         */
        return $data;
    }

    public function randomValue(array $array)
    {
        return $array[array_rand($array, 1)];
    }
}
