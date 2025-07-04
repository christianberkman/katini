<?php

namespace App\Commands\Katini;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CompileSidebar extends BaseCommand
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
    protected $name = 'compile:sidebar';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Compile the sidebar HTML and save to file';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'compile:sidebar';

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
     * Sidebar structure
     */
    protected $sidebar = [
        'groups' => [
            [
                'name'  => 'dashboard',
                'title' => 'Dashboard',
                'icon'  => 'grid',
                'href'  => '/dashboard',
            ],
            [
                'name'  => 'supporters',
                'title' => 'Supporters',
                'icon'  => 'people',
                'href'  => 'supporters',
                'items' => [
                    [
                        'title' => 'Overzicht',
                        'href'  => '/supporters',
                    ],
                    [
                        'title' => 'Alle supporters',
                        'href'  => '/supporters/all',
                    ],
                    [
                        'title' => 'Supporter vinden',
                        'href'  => '/supporters/find',
                    ],
                    [
                        'title' => 'Supporter toevoegen',
                        'href'  => '/supporters/new',
                    ],
                ],
            ],
            [
                'name'  => 'circles',
                'title' => 'Kringen',
                'href'  => 'circles',
                'icon'  => 'circle',
            ],
            [
                'name'  => 'donations',
                'title' => 'Donaties',
                'icon'  => 'donation',
                'href'  => 'donations',
                'items' => [
                    [
                        'title' => 'Overzicht',
                        'href'  => '/donations',
                    ],
                    [
                        'title' => 'Alle donaties',
                        'href'  => '/donations/all',
                    ],
                    [
                        'title' => 'Donatie toevoegen',
                        'href'  => '/donations/new',
                    ],
                ],
            ],
            [
                'name'  => 'settings',
                'title' => 'Instellingen',
                'icon'  => 'gear',
                'href'  => 'settings',
            ],
            [
                'name'  => 'logout',
                'title' => 'Uitloggen',
                'icon'  => 'box-arrow-right',
                'href'  => 'logout',
            ],
        ],
    ];

    /**
     * Output HTML
     */
    protected $output = '';

    /**
     * Backup file
     */
    protected $backupFile = APPPATH . '/Views/sidebar.bak.php';

    /**
     * Output file
     */
    protected $outputFile = APPPATH . '/Views/sidebar.php';

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        helper('filesystem');
        helper('bootstrap_icons');

        // Make backup file
        $backup = copy($this->outputFile, $this->backupFile);
        if ($backup) {
            CLI::write('Saved backup to ' . $this->backupFile, 'green');
        } else {
            CLI::error('Could not save backup file to ' . $this->backupFile);
            CLI::error('Aborting');

            return false;
        }

        // Start output
        $this->openContent();

        // Iterate through groups
        foreach ($this->sidebar['groups'] as $group) {
            if (! array_key_exists('items', $group)) {
                $this->appendMain($group);
            } else {
                $this->appendMainWithCollapse($group);

                $this->openCollapse($group);

                foreach ($group['items'] as $item) {
                    $this->appendItem($item);
                }

                $this->closeCollapse();
            }
        }

        // End output
        $this->closeContent();

        // Attempt write
        $write = write_file($this->outputFile, $this->output);
        if ($write) {
            CLI::write('Output written to file: ' . $this->outputFile, 'green');

            return;
        }
        CLI::error('Could not write output to file: ' . $this->outputFile);
    }

    /**
     * Open the sidebar HTML content
     */
    private function openContent(): void
    {
        $this->output .= '<div class="sidebar-content">' . PHP_EOL;
    }

    /**
     * Append a main link that will not be followed by items
     */
    private function appendMain(array $group): void
    {
        $this->output .= "\t<!--{$group['name']}-->" . PHP_EOL;
        $this->output .= "\t\t<div class=\"sidebar-main <?=url_is(\"{$group['href']}*\") ? 'active' : '';?>\">" . PHP_EOL;
        $this->output .= "\t\t\t<a href=\"<?=site_url('{$group['href']}');?>\" class=\"stretched-link\">" . PHP_EOL;
        $this->output .= "\t\t\t\t" . bi($group['icon']) . " {$group['title']}" . PHP_EOL;
        $this->output .= "\t\t\t</a>" . PHP_EOL;
        $this->output .= "\t\t</div>" . PHP_EOL;
        $this->output .= PHP_EOL;
    }

    /**
     * Append a main link that will be followed by items
     */
    private function appendMainWithCollapse(array $group)
    {
        $this->output .= "\t<!--{$group['name']}-->" . PHP_EOL;
        $this->output .= "\t\t<div class=\"sidebar-main <?=url_is('{$group['href']}') ? 'active' : '';?>\">" . PHP_EOL;
        $this->output .= "\t\t\t<a href=\"<?=site_url('{$group['href']}');?>\" class=\"stretched-link\">" . PHP_EOL;
        $this->output .= "\t\t\t\t" . bi($group['icon']) . " {$group['title']}" . PHP_EOL;
        $this->output .= "\t\t\t</a>" . PHP_EOL;
        $this->output .= "\t\t</div>" . PHP_EOL;
    }

    /**
     * Open sidebar-sub collapse group
     */
    private function openCollapse(array $group): void
    {
        $this->output .= "\t\t<ul class=\"sidebar-sub mb-0 collapse <?= url_is('{$group['href']}*') ? 'show' : ''; ?>\" id=\"sidebar-{$group['name']}\">" . PHP_EOL;
    }

    /**
     * Append item
     */
    private function appendItem(array $item)
    {
        $this->output .= "\t\t\t<li class=\"<?= url_is('{$item['href']}') ? 'active' : ''; ?>\">" . PHP_EOL;
        $this->output .= "\t\t\t\t<a href=\"<?= site_url('{$item['href']}'); ?>\" class=\"stretched-link\">{$item['title']}</a>" . PHP_EOL;
        $this->output .= "\t\t\t</li>" . PHP_EOL;
    }

    /**
     * Close sidebar-sub collapse group
     */
    private function closeCollapse(): void
    {
        $this->output .= "\t\t</ul>" . PHP_EOL;
        $this->output .= PHP_EOL;
    }

    /**
     * Close the sidebar HTML content
     */
    private function closeContent(): void
    {
        $this->output .= '</div><!--/sidebar-content-->' . PHP_EOL;
    }
}
