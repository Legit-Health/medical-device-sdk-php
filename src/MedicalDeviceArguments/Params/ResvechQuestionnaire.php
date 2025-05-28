<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class ResvechQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $woundDimensions,
        public int $tissues,
        public int $edges,
        public int $tissueInWoundBed,
        public int $exudate,
        public int $frequencyOfPain,
        public int $macerationAroundWound,
        public int $tunneling,
        public int $increasingPain,
        public int $erythemaAroundWound,
        public int $edemaAroundWound,
        public int $temperatureRise,
        public int $increasingExudate,
        public int $purulentExudate,
        public int $tissueFriableOrBleedsEasily,
        public int $stationaryWound,
        public int $biofilmCompatibleTissue,
        public int $odor,
        public int $hypergranulation,
        public int $increasingWound,
        public int $satelliteLesions,
        public int $tissuePaleness
    ) {
        $this->ensureIsInRange($woundDimensions, 0, 4, 'woundDimensions');
        $this->ensureIsInRange($tissues, 0, 4, 'tissues');
        $this->ensureIsInRange($edges, 0, 4, 'edges');
        $this->ensureIsInRange($tissueInWoundBed, 0, 4, 'tissueInWoundBed');
        $this->ensureIsInRange($exudate, 0, 4, 'exudate');
        $this->ensureIsInRange($frequencyOfPain, 0, 4, 'frequencyOfPain');
        $this->ensureIsInRange($macerationAroundWound, 0, 1, 'macerationAroundWound');
        $this->ensureIsInRange($tunneling, 0, 4, 'tunneling');
        $this->ensureIsInRange($increasingPain, 0, 4, 'increasingPain');
        $this->ensureIsInRange($erythemaAroundWound, 0, 4, 'erythemaAroundWound');
        $this->ensureIsInRange($edemaAroundWound, 0, 4, 'edemaAroundWound');
        $this->ensureIsInRange($temperatureRise, 0, 1, 'temperatureRise');
        $this->ensureIsInRange($increasingExudate, 0, 4, 'increasingExudate');
        $this->ensureIsInRange($purulentExudate, 0, 1, 'purulentExudate');
        $this->ensureIsInRange($tissueFriableOrBleedsEasily, 0, 4, 'tissueFriableOrBleedsEasily');
        $this->ensureIsInRange($stationaryWound, 0, 4, 'stationaryWound');
        $this->ensureIsInRange($biofilmCompatibleTissue, 0, 4, 'biofilmCompatibleTissue');
        $this->ensureIsInRange($odor, 0, 4, 'odor');
        $this->ensureIsInRange($hypergranulation, 0, 4, 'hypergranulation');
        $this->ensureIsInRange($increasingWound, 0, 4, 'increasingWound');
        $this->ensureIsInRange($satelliteLesions, 0, 4, 'satelliteLesions');
        $this->ensureIsInRange($tissuePaleness, 0, 4, 'tissuePaleness');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Resvech->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'woundDimensions' => $this->woundDimensions,
                    'tissues' => $this->tissues,
                    'edges' => $this->edges,
                    'tissueInWoundBed' => $this->tissueInWoundBed,
                    'exudate' => $this->exudate,
                    'frequencyOfPain' => $this->frequencyOfPain,
                    'macerationAroundWound' => $this->macerationAroundWound,
                    'tunneling' => $this->tunneling,
                    'increasingPain' => $this->increasingPain,
                    'erythemaAroundWound' => $this->erythemaAroundWound,
                    'edemaAroundWound' => $this->edemaAroundWound,
                    'temperatureRise' => $this->temperatureRise,
                    'increasingExudate' => $this->increasingExudate,
                    'purulentExudate' => $this->purulentExudate,
                    'tissueFriableOrBleedsEasily' => $this->tissueFriableOrBleedsEasily,
                    'stationaryWound' => $this->stationaryWound,
                    'biofilmCompatibleTissue' => $this->biofilmCompatibleTissue,
                    'odor' => $this->odor,
                    'hypergranulation' => $this->hypergranulation,
                    'increasingWound' => $this->increasingWound,
                    'satelliteLesions' => $this->satelliteLesions,
                    'tissuePaleness' => $this->tissuePaleness,
                ]
            ]
        ];
    }
}
