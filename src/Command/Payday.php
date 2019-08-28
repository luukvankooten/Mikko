<?php
namespace Mikko\Command;

use Mikko\Service\Salary;
use Mikko\Service\CsvStructure;
use Mikko\Service\Salaries;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Payday extends Command
{

    protected static $defaultName = 'payday';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Get a csv of the payday.')
            ->addArgument('file', InputArgument::REQUIRED, 'The path to CSV file');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Mikko\Exception\UnexpectedCsvHeader
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');

        $date = new \DateTimeImmutable();

        $salaries = (new Salaries($date))
            ->getSalaries();

        $bytes = (new CsvStructure($file))
            ->insertAll(array_map([$this, 'csvLine'], $salaries))
            ->save();

        if ($bytes > 0) {
          $output->writeln(
              sprintf('Successfully added to %s.', $file)
          );

          return;
        }

        $output->writeln('Something is just not ok.');
    }

    private function csvLine(Salary $salary)
    {
        return [$salary->dateTime, $salary->getSalary(), $salary->bonus()];
    }
}