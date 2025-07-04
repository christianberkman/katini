<?php

namespace App\Commands\Katini;

use App\Entities\Supporter;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class GenerateSupporter extends BaseCommand
{
    protected $group       = 'Katini';
    protected $name        = 'generate:supporter';
    protected $description = 'Generate a random supporter';
    protected $arguments   = [
        'number' => 'Number of supporters to generate (default: 1)',
    ];
    protected Supporter $supporter;

    public function run(array $params)
    {
        helper('common_helper');

        $number = (int) ($params[0] ?? 1);

        // Generate supporter
        $successCounter = 0;

        for ($i = 1; $i <= $number; $i++) {
            $errorCount = 0;
            $this->generateSupporter();

            // Show info
            if ($number === 1) {
                $attributes = [];

                foreach ($this->supporter->toArray() as $key => $value) {
                    $attributes[] = [$key, $value];
                }

                CLI::write('Generated supporter:');
                CLI::newLine();
                CLI::table($attributes, ['Key', 'Value']);
            }

            // Insert into database
            $db             = db_connect();
            $supporterModel = model('SupporterModel');
            $insert         = $supporterModel->insert($this->supporter);

            // Error, try again
            if (! $insert) {
                $errorCount++;

                CLI::write('Error generating supporter. Trying again.', 'red');

                if ($errorCount <= 3) {
                    $i--;

                    continue;
                }
                CLI::newLine();
                CLI::write('Too many errors. Aborting.', 'red');
            }

            $successCounter++;

            CLI::write("Generated supporter '{$this->supporter->compileDisplayName()}' added to database", 'green');
        }

        CLI::newLine();
        CLI::write("Generated and inserted {$successCounter} supporters.", 'green');

        return EXIT_SUCCESS;
    }

    /**
     * Generate a random supporter
     */
    public function generateSupporter(): Supporter
    {
        $this->supporter = new Supporter();

        $this->supporter->last_name  = $this->generateLastName();
        $this->supporter->first_name = $this->generateFirstName();
        $this->supporter->infix      = $this->generateInfix();
        $this->supporter->title      = $this->generateTitle();
        $this->supporter->orgName    = $this->generateOrgName();
        $this->supporter->phone      = $this->generatePhone();
        $this->supporter->email      = $this->generateEmail();
        $this->generateAddress($this->supporter);
        $this->supporter->date_birth = $this->generateBirthday();
        $this->supporter->iban       = $this->generateIban();
        $this->supporter->note       = 'Gegenereerd als test via het commando spark katini:generate:supporter';

        return $this->supporter;
    }

    /**
     * Pick a random item from the arguments
     */
    private function pickFrom(...$args)
    {
        return $args[array_rand($args)];
    }

    /**
     * Generate initials or first name
     */
    private function generateFirstName(): ?string
    {
        $seed = random_int(1, 100);

        // No first name
        if ($seed <= 10) {
            return null;
        }

        // Initials
        if ($seed <= 25 && ! empty($this->supporter->last_name)) {
            return chr(mt_rand(65, 90)) . '.';
        }
        if ($seed <= 40 && ! empty($this->supporter->last_name)) {
            return chr(mt_rand(65, 90)) . '.' . chr(mt_rand(65, 90)) . '.';
        }

        // Full name

        return $this->pickFrom('Jan', 'Piet', 'Simon', 'Jaap', 'Nel', 'Eric', 'Marie', 'Elliot', 'Patricia');
    }

    /**
     * Generate last name
     */
    private function generateLastName(): ?string
    {
        $seed = random_int(1, 100);

        // No last name
        if ($seed <= 10) {
            return null;
        }

        // Last name
        return $this->pickFrom('Boer', 'Schaap', 'Gracht', 'Kanaal', 'Molen', 'Berg', 'Leeuwen', 'Brie', 'Stokvis', 'Kooiman', 'Oranje');
    }

    /**
     * Generate infix if last name is not null
     */
    private function generateInfix(): ?string
    {
        // No infix if no last name
        if (empty($this->supporter->last_name)) {
            return null;
        }

        $seed = random_int(0, 100);

        // No infix
        if ($seed <= 70) {
            return null;
        }

        // Infix
        return $this->pickFrom('van', 'van der', 'van de', 'v.d.', '\'t', 'de');
    }

    /**
     * Generate an org name
     */
    private function generateOrgName(): ?string
    {
        $seed = random_int(1, 100);

        // No org name
        if ($seed <= 75) {
            return null;
        }

        // Org name
        return $this->pickFrom('Acme', 'Supermarkt B.V.', 'Dorpskerk', 'Bouwbedrijf Bob', 'Kaashandel Gouda');
    }

    /**
     * Generate a title
     */
    private function generateTitle(): ?string
    {
        $seed = random_int(1, 100);

        // No title
        if ($seed <= 50) {
            return null;
        }

        // Title
        $titleList = katini()->getList('titles');

        return $this->pickFrom(...$titleList['items']);
    }

    /**
     * Generate a phone number
     */
    private function generatePhone(): ?string
    {
        $seed = random_int(1, 100);

        // No phone number
        if ($seed <= 10) {
            return null;
        }

        // Phone number
        $phone = '0' . random_int(100000000, 999999999);

        return (string) $phone;
    }

    /**
     * Generate an email address
     */
    private function generateEmail(): ?string
    {
        $seed = random_int(1, 100);

        // No e-mail
        if ($seed <= 10) {
            return null;
        }

        // Org email
        if (! empty($this->supporter->org_name)) {
            return 'info@' . str_replace([' ', '.'], '', $this->supporter->org_name) . 'nl';
        }

        // Name email
        return strtolower(str_replace([' ', '\'', '.'], '', implode('.', [$this->supporter->first_name, $this->supporter->infix, $this->supporter->last_name]))) . '@mail.nl';
    }

    /**
     * Generate an address and add to specified supporter
     */
    public function generateAddress(Supporter $supporter): void
    {
        $seed = random_int(1, 100);

        // No address
        if ($seed <= 30) {
            return;
        }

        // Address
        $supporter->address_street   = $this->pickFrom('Hoofdstraat', 'Lange weg', 'Oosteinde', 'Uitzendweg', 'Stationsstraat');
        $supporter->address_number   = random_int(1, 1000);
        $supporter->address_addition = $this->pickFrom(null, null, null, null, 'A');
        $supporter->address_postcode = random_int(1111, 9999) . ' ' . $this->pickFrom('AA', 'ES', 'SV', 'UG');
        $supporter->address_city     = $this->pickFrom('Den Haag', 'Amsterdam', 'Rotterdam', 'Zwammerdam', 'Delft', 'Hendrik-Ido-Ambacht', 'Pijnacker');
    }

    /**
     * Generate a birthday
     */
    public function generateBirthday(): ?string
    {
        $seed = random_int(1, 100);

        // No Birthday
        if ($seed <= 50) {
            return null;
        }

        // Birthday
        return random_int(1950, (date('Y') - 8)) . '-' . random_int(1, 12) . '-' . random_int(1, 28);
    }

    /**
     * Generate an IBAN
     */
    public function generateIban(): ?string
    {
        $seed = random_int(1, 100);

        // No IBAN
        if ($seed <= 60) {
            return null;
        }

        // IBAN
        return 'NL92' . $this->pickFrom('INGB', 'ABNA', 'RABO', 'BUNQ') . random_int(11111111, 99999999);
    }
}
