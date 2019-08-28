<?php
namespace Mikko\Service;

use DateTimeImmutable;
use League\Csv\Reader;
use League\Csv\Writer;
use Mikko\Exception\UnexpectedCsvHeader;

class CsvStructure
{
    /**
     * The headers for the csv.
     *
     * @var array
     */
    protected $headers = ['Month name', 'Salary payment date', 'Bonus payment date'];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var $path
     */
    protected $path;

    /**
     * CSV constructor.
     *
     * @param string $path
     *
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            touch($path);
        }

        $this->setCorrectHeaders();
        $this->path = $path;
    }

    /**
     * Insert an multidimensional array to the csv.
     *
     * @param array $array
     *
     * @return \Mikko\Service\CsvStructure
     */
    public function insertAll(array $array): self
    {
        foreach ($array as $arr) {
            $this->insert(...$arr);
        }

        return $this;
    }

    /**
     * Insert the required data in the csv.
     *
     * @param \DateTimeImmutable $month
     * @param \DateTimeImmutable $salaryDate
     * @param \DateTimeImmutable $bonusDate
     *
     * @return self
     */
    public function insert(DateTimeImmutable $month, DateTimeImmutable $salaryDate, DateTimeImmutable $bonusDate): self
    {
        $format = 'l';

        $this->data[] = [
            $month->format('F'),
            $salaryDate->format($format),
            $bonusDate->format($format),
        ];

        return $this;
    }

    /**
     * Get the csv content.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set the required headers/columns in the csv.
     */
    protected function setCorrectHeaders(): void
    {
        $this->data[0] = $this->headers;
    }

    /**
     * Save the data to the array.
     *
     * @return int
     */
    public function save(): int
    {
        unlink($this->path);

        return Writer::createFromPath($this->path,'w+')
            ->insertAll($this->getData());
    }
}