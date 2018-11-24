<?php
/**
 * User: Samuel Martins
 * Date: 23/11/18
 * Time: 20:18
 */

namespace Tests;

use App\HoursWorkedCalcCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class HoursWorkedCalcCommandTest extends TestCase
{
    public function providePeriodsToValidate()
    {
        return [
            [
                [
                    'day'      => '2018-11-23',
                    'start'    => '2018-11-23 11:40',
                    'pause'    => '12:46',
                    'continue' => '14:02',
                    'finish'   => '20:00',
                ],
                [
                    'start'
                ],
            ],
            [
                [
                    'day'      => '2018-11-23',
                    'start'    => '11-23-2018 9:00',
                    'pause'    => '12:46',
                    'continue' => '12:46',
                    'finish'   => 'hoje',
                ],
                [
                    'start',
                    'finish',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providePeriodsToValidate
     * @param array $args
     * @param array $invalidArgs
     */
    public function should_accept_just_valid_date_and_time_on_args(array $args, array $invalidArgs)
    {
        $commandTester = new CommandTester(new HoursWorkedCalcCommand());
        $commandTester->execute($args);

        $output = $commandTester->getDisplay();

        foreach ($invalidArgs as $arg) {
            $this->assertContains( "Invalid time on param: [$arg]", $output);
        }
    }

    public function provideValidPeriods()
    {
        return [
            [
                [
                    'day'      => '2018-11-21',
                    'start'    => '08:52',
                    'pause'    => '12:23',
                    'continue' => '13:24',
                    'finish'   => '18:56',
                ],
                9.05
            ]
        ];
    }

    /**
     * @test
     * @dataProvider provideValidPeriods
     * @param array $args
     * @param float $hours
     */
    public function should_return_the_worked_hours_after_pass_correct_period(array $args, float $hours)
    {
        $commandTester = new CommandTester(new HoursWorkedCalcCommand());
        $commandTester->execute($args);

        $output = $commandTester->getDisplay();

        $this->assertContains("You worked: {$hours}", $output);
    }
}
