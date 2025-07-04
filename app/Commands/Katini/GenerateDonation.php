<?php

namespace App\Commands\Katini;

use App\Entities\Donation;
use App\Models\DonationModel;
use App\Models\SupporterModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class GenerateDonation extends BaseCommand
{
    protected $group       = 'Katini';
    protected $name        = 'generate:donation';
    protected $description = 'Generate one or more random donations';
    protected $arguments   = [
        'number' => 'Number of donations to generate (default: 1)',
    ];
    protected Donation $donation;
    protected DonationModel $donationModel;
    protected SupporterModel $supporterModel;

    public function run(array $params)
    {
        helper('common_helper');
        $this->donationModel  = model('DonationModel');
        $this->supporterModel = model('SupporterModel');

        $number = (int) ($params[0] ?? 1);

        // Generate donations
        $successCounter = 0;

        for ($i = 1; $i <= $number; $i++) {
            $errorCount = 0;

            $this->generateDonation();

            // Show info
            if ($number === 1) {
                $attributes = [];

                foreach ($this->donation->toArray() as $key => $value) {
                    $attributes[] = [$key, $value];
                }

                CLI::write('Generated donation:');
                CLI::newLine();
                CLI::table($attributes, ['Key', 'Value']);
            }

            // Insert into database
            $db     = db_connect();
            $insert = $this->donationModel->insert($this->donation);

            // Error, try again
            if (! $insert) {
                $errorCount++;

                if ($errorCount <= 3) {
                    $i--;
                    CLI::write('Error generating donation. Trying again.');

                    continue;
                }
                CLI::newLine();
                CLI::write('Too many errors. Aborting', 'red');
            }

            $successCounter++;

            CLI::write("Generated donation of {$this->donation->amount} ({$this->donation->method})", 'green');
        }

        CLI::newLine();
        CLI::write("Generated and inserted {$successCounter} donations.", 'green');

        return EXIT_SUCCESS;
    }

    /**
     * Generate a random supporter
     */
    public function generateDonation(): Donation
    {
        $this->donation               = new Donation();
        $this->donation->id = $this->pickSupporterId();
        $this->donation->amount       = (random_int(250, 10000) / 100);
        $this->donation->method       = $this->pickPaymentMethod();

        return $this->donation;
    }

    /**
     * Pick a random item from the arguments
     */
    private function pickFrom(...$args)
    {
        return $args[array_rand($args)];
    }

    /**
     * Pick a random supporter
     */
    public function pickSupporterId(): ?int
    {
        $seed = random_int(1, 100);

        // Anonymous donation
        if ($seed <= 10) {
            return null;
        }

        // Donation with supporter
        $row = $this->supporterModel
            ->select('id')
            ->orderBy('id', 'RANDOM')
            ->asArray()
            ->first();

        return $row['id'];
    }

    /**
     * Pick a payment method
     */
    private function pickPaymentMethod(): string
    {
        $paymentMethods = katini()->getList('paymentMethods')['items'];

        return $this->pickFrom(...$paymentMethods);
    }
}
