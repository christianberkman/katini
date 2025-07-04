<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;
use Throwable;

class Recurring extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Katini';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'katini:recurring';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Process recurring donations';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'katini:recurring [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--list-only',
    ];

    protected $model;

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        helper('common');

        $this->model = model('DonationModel');

        $donations = $this->findReady();

        if (count($donations) > 0) {
            CLI::write(count($donations) . ' donation ready for processing...', 'green');

            while (count($donations) > 0) {
                foreach ($donations as $donation) {
                    CLI::print("{$donation->getTitle()} due on {$donation->next_date->toDateString()}");

                    try {
                        $nextDonation              = clone $donation;
                        $nextDonation->id = null;
                        $nextDonation->created_at  = $donation->next_date;
                        $nextDonation->updateRecurring();

                        $donation->has_recurred = 1;

                        $this->model->insert($nextDonation);
                        $this->model->save($donation);
                        CLI::print(" [OK] Next donation due on {$nextDonation->next_date->toDateString()}", 'green');
                    } catch (Throwable $e) {
                        CLI::print(" [ERROR: {$e->getMessage()}]", 'red');
                    }

                    CLI::print(PHP_EOL);
                }

                $donations = $this->findReady();
            }
        } else {
            CLI::write('No donations ready for processing', 'yellow');
        }

        CLI::newLine();
    }

    /**
     * Find recurring donations that are ready to reccur
     */
    private function findReady(): array
    {
        return $this->model
            ->where('is_recurring', 1)
            ->where('has_recurred', 0)
            ->where('next_date <=', Time::today()->toDateString())
            ->findAll();
    }
}
