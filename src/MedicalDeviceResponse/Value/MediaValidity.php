<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use InvalidArgumentException;

final readonly class MediaValidity
{
    /**
     * @param array{"quality":ValidityMetric,"domain":ValidityMetric} $metrics
     */
    public function __construct(
        public bool $isValid,
        public array $metrics
    ) {
    }

    public static function fromJson(array $json): self
    {
        $validityMetrics = [];
        foreach ($json['metrics'] as $name => $record) {
            $validityMetrics[$name] = new ValidityMetric(
                $name,
                $record['isValid'],
                $record['score'],
                $record['category']
            );
        }

        if (!isset($validityMetrics['quality'])) {
            throw new InvalidArgumentException('The metrics array does not contain quality key');
        }
        if (!isset($validityMetrics['domain'])) {
            throw new InvalidArgumentException('The metrics array does not contain domain key');
        }

        return new self(
            $json['isValid'],
            /** @var  array{"quality":ValidityMetric,"domain":ValidityMetric} */
            $validityMetrics
        );
    }

    public function getFailedValidityMetric(): ?ValidityMetric
    {
        foreach ($this->metrics as $validityMetric) {
            if (!$validityMetric->isValid) {
                return $validityMetric;
            }
        }
        return null;
    }

    public function getDiqaScore(): float
    {
        return $this->metrics['quality']->score;
    }
}
