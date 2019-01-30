<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Tests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa os testes da aplicação';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Executa o camando para executar os testes de estilo de código.
     *
     * @return void
     */
    private function codestyle()
    {
        $command = "vendor/bin/phpcs --standard=PSR2 --extensions=php app";
        $process = new Process($command);
        $process->run();
        echo $process->getOutput();
    }

    /**
     * Executa o camando para executar os testes unitário e integração.
     *
     * @return void
     */
    private function unitAndIntegration()
    {
        $command = "vendor/bin/phpunit --coverage-text --colors=never";
        $process = new Process($command);
        $process->run();
        echo $process->getOutput();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->codestyle();
        $this->unitAndIntegration();
    }
}