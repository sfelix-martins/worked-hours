<?php

namespace App;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HoursWorkedCalcCommand extends Command
{
    /**
     * @var bool
     */
    private $hasValidationErrors = false;

    protected function configure()
    {
        $this
            ->setName('worked:hours')
            ->setDescription('Calculate how many hours you worked!')
            ->addArgument(
                'day',
                InputArgument::REQUIRED,
                'What day do you want?'
            )
            ->addArgument(
                'start',
                InputArgument::REQUIRED,
                'What time did you start working?'
            )
            ->addArgument(
                'pause',
                InputArgument::REQUIRED,
                'What time did you pause to lunch?'
            )
            ->addArgument(
                'continue',
                InputArgument::REQUIRED,
                'What time did you get back to work?'
            )
            ->addArgument(
                'finish',
                InputArgument::REQUIRED,
                'What time did you finish the work?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = $this->getParsedArgs($input->getArguments(), $output);

        if ($this->hasValidationErrors) {
            return;
        }

        $firstPeriodMin  = $args['start']->diffInMinutes($args['pause']);
        $secondPeriodMin = $args['continue']->diffInMinutes($args['finish']);

        $totalMin = $firstPeriodMin + $secondPeriodMin;
        $totalHours = round($totalMin / 60, 2);

        $table = new Table($output);
        $table
            ->setHeaders(['Day', 'Start', 'Pause', 'Continue', 'Finish', 'Total'])
            ->addRow([
                $args['day']->toDateString(),
                $args['start']->toTimeString(),
                $args['pause']->toTimeString(),
                $args['continue']->toTimeString(),
                $args['finish']->toTimeString(),
                "You worked: {$totalHours} hrs"
            ]);

        $table->render();
    }

    /**
     * @param array $args
     * @param OutputInterface $output
     * @return Carbon[]
     */
    public function getParsedArgs(array $args, OutputInterface $output): array
    {
        $parsed = [];
        try {
            /** @var Carbon $day */
            $day = Carbon::parse($args['day']);
            $parsed['day'] = $day;
        } catch (\Exception $e) {
            $output->writeln("<error>Invalid date on param [day]</error>");
            $this->hasValidationErrors = true;

            return $parsed;
        }

        foreach ($args as $arg => $value) {
            if ($arg === 'command' || $arg === 'day') {
                continue;
            }
            try {
                $parsed[$arg] = Carbon::parse("{$day->toDateString()} $value");
            } catch (\Exception $e) {
                $output->writeln("<error>Invalid time on param: [$arg]</error>");
                $this->hasValidationErrors = true;
            }
        }

        return $parsed;
    }
}