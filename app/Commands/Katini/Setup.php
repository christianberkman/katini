<?php

namespace App\Commands\Katini;

use App\Entities\User;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Setup extends BaseCommand
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
    protected $name = 'katini:setup';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Setup Katini installation';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'katini:setup';

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
    protected $options = [];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        CLI::clearScreen();

        CLI::write('Katini Setup', 'blue');
        CLI::write('==============================================', 'blue');
        CLI::newLine();

        CLI::write('Dit script voert de volgende acties uit');
        CLI::write("\t* Database migraties");
        CLI::write("\t* Gebruiker voor beheerder aanmaken");
        CLI::write("\t* Testdata aanmaken (optioneel)");
        CLI::newLine();

        // Database migrations
        command('migrate --all');
        CLI::newLine();

        // Settings
        $this->settings();

        // Create user
        $this->createManager();

        // Sample data
        $this->generateSampleData();

        // Done
        CLI::write('Voltooid', 'green');
        CLI::newLine();

        return 1;
    }

    /**
     * Set settings
     */
    private function settings()
    {
        $appName = CLI::prompt('Appnaam', setting('Katini.appName'));
        setting('Katini.appName', $appName);

        $teamName = CLI::prompt('Teamnaam', setting('Katini.teamName'));
        setting('Katini.teamName', $teamName);

        CLI::write('Tijdzones kunnen ingesteld worden via instellingen (algemeen)', 'yellow');

        CLI::write('Opgeslagen', 'green');
        CLI::newLine();
    }

    /**
     * Create the manager user
     */
    public function createManager()
    {
        $userModel = auth()->getProvider();

        // Check if user with ID 1 already exists
        $userCheck = $userModel->findByID(1);
        if ($userCheck !== null) {
            CLI::write('Er is al een gebruiker aan wezig met ID 1: ' . $userCheck->email, 'yellow');
            $abort = CLI::prompt('Afbreken?', ['y', 'n']);
            if ($abort === 'y') {
                return 0;
            }
        }

        CLI::write('Gebruiker voor beheerder aanmaken');
        $data               = [];
        $data['first_name'] = CLI::prompt('Voornaam');
        $data['last_name']  = CLI::prompt('Achternaam');
        $data['email']      = CLI::prompt('E-mailadres');

        $passwordCheck = false;

        while ($passwordCheck === false) {
            $password = CLI::prompt('Wachtwoord');
            if (strlen($password) < 8) {
                CLI::write('Wachtwoord moet minimaal 8 tekens lang zijn', 'red');

                continue;
            }

            $passwordConfirm = CLI::prompt('Bevestig wachtwoord');
            if ($password !== $passwordConfirm) {
                CLI::write('Wachtworden komen niet overeen, probeer opnieuw', 'red');

                continue;
            }

            $data['password'] = $password;
            $passwordCheck    = true;
        }

        // Confirm User
        CLI::newLine();
        CLI::write("Naam: {$data['first_name']} {$data['last_name']}");
        CLI::write("Email: {$data['email']}");
        CLI::write("Wachtwoord: {$data['password']}");
        $confirm = CLI::prompt('Bevestigen', ['y', 'n']);

        if ($confirm === 'n') {
            CLI::write('Afgebroken', 'red');

            return 0;
        }

        // Insert user
        $newUser = new User($data);
        $userModel->save($newUser);

        // Add to manager group
        $user = $userModel->findById($userModel->getInsertID());

        // Add to default group
        $user->addGroup('manager');
    }

    protected function generateSampleData()
    {
        $confirm = CLI::prompt('Database vullen met voorbeelden', ['y', 'n']);
        if ($confirm === 'n') {
            return;
        }

        command('generate:supporter 10');
        command('generate:donation 10');
    }
}
