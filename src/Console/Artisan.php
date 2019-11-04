<?php

namespace EderSoares\Laravel\Tenant\Console;

use EderSoares\Laravel\Tenant\Contracts\Tenant;
use EderSoares\Laravel\Tenant\Contracts\TenantManager;
use EderSoares\Laravel\Tenant\Contracts\TenantRepository;
use Illuminate\Console\Application;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Artisan extends Application
{
    /**
     * {@inheritdoc}
     */
    protected function getDefaultInputDefinition()
    {
        return tap(parent::getDefaultInputDefinition(), function ($definition) {
            $definition->addOption($this->getTenantOption());
            $definition->addOption($this->getTenantsOption());
        });
    }

    /**
     * Get the global tenant option for the definition.
     *
     * @return InputOption
     */
    protected function getTenantOption()
    {
        $message = 'Run command in specific tenant';

        return new InputOption('--tenant', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, $message);
    }

    /**
     * Get the global tenants option for the definition.
     *
     * @return InputOption
     */
    protected function getTenantsOption()
    {
        $message = 'Run command in all tenants';

        return new InputOption('--tenants', null, InputOption::VALUE_NONE, $message);
    }

    /**
     * @return TenantRepository
     */
    protected function getTenantRepository()
    {
        return $this->laravel->get(TenantRepository::class);
    }

    /**
     * @return TenantManager
     */
    protected function getTenantManager()
    {
        return $this->laravel->get(TenantManager::class);
    }

    /**
     * Run command in all or specific tenants.
     *
     * @param InputInterface|null  $input
     * @param OutputInterface|null $output
     * @param array                $specificTenants
     *
     * @return void
     */
    protected function runTenants(
        InputInterface $input = null,
        OutputInterface $output = null,
        $specificTenants = []
    ) {
        $tenants = $this->getTenantRepository()->getTenants($specificTenants);

        if (empty($tenants->count())) {
            $output->writeln('<error>No defined tenant.</error>');

            return;
        }

        if ($specificTenants) {
            $output->writeln('<info>Running tenants..</info>');
        } else {
            $output->writeln('<info>Running all tenants..</info>');
        }

        $tenants->each(function (Tenant $tenant) use ($input, $output) {
            $this->getTenantManager()->swap($tenant);

            $output->writeln('');
            $output->writeln("Running: <info>{$tenant->getIdentifier()}</info>");

            parent::run($input, $output);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function run(
        InputInterface $input = null,
        OutputInterface $output = null
    ) {
        try {
            // Makes ArgvInput::getFirstArgument() able to distinguish an option from an argument.
            $input->bind($this->getDefinition());
        } catch (ExceptionInterface $e) {
            // Errors must be ignored, full binding/validation happens later when the command is known.
        }

        if ($input->getOption('tenants')) {
            $this->runTenants($input, $output);

            return;
        }

        if ($specificTenants = $input->getOption('tenant')) {
            collect($specificTenants)->each(function ($tenant) use ($output) {
                $output->writeln("  {$tenant}");
            });

            $this->runTenants($input, $output, $specificTenants);

            return;
        }

        parent::run($input, $output);
    }
}
