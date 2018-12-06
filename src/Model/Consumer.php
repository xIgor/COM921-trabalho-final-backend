<?php

namespace IntecPhp\Model;

use IntecPhp\Entity\ConsumerEntity;

class Consumer
{
    const NORDESTE     = 'NE';
    const SUDESTE      = 'SE';
    const SUL          = 'S';
    const CENTRO_OESTE = 'CO';
    const NORTE        = 'N';



    private $consumerEntity;

    public function __construct(ConsumerEntity $consumerEntity)
    {
        $this->consumerEntity = $consumerEntity;
    }

    public function calculateRegionsComplaintRate()
    {
        $total_r  = $this->consumerEntity->countTotalRecords();
        $total = $total_r ? (int)$total_r['total'] : 0;
        $total_ne = $this->consumerEntity->countRegionRecords(self::NORDESTE);
        $ne = $total_ne ? (int)$total_ne['total'] : 0;
        $total_se = $this->consumerEntity->countRegionRecords(self::SUDESTE);
        $se = $total_se ? (int)$total_se['total'] : 0;
        $total_s  = $this->consumerEntity->countRegionRecords(self::SUL);
        $s = $total_s ? (int)$total_s['total'] : 0;
        $total_co = $this->consumerEntity->countRegionRecords(self::CENTRO_OESTE);
        $co = $total_co ? (int)$total_co['total'] : 0;
        $total_n  = $this->consumerEntity->countRegionRecords(self::NORTE);
        $n = $total_n ? (int)$total_n['total'] : 0;

        $regions = [
            self::NORDESTE     => round(($ne / $total) * 100, 2),
            self::SUDESTE      => round(($se / $total) * 100, 2),
            self::SUL          => round(($s / $total) * 100, 2),
            self::CENTRO_OESTE => round(($co / $total) * 100, 2),
            self::NORTE        => round(($n / $total) * 100, 2)
        ];
        asort($regions);

        return $regions;
    }

    public function getStates(string $region)
    {
        if ($region == self::NORDESTE) {
            return [
                'MA',
                'PI',
                'CE',
                'RN',
                'PE',
                'PB',
                'SE',
                'AL',
                'BA'
            ];
        } elseif ($region == self::SUDESTE) {
            return [
                'SP',
                'RJ',
                'ES',
                'MG'
            ];
        } elseif ($region == self::SUL) {
            return [
                'PR',
                'RS',
                'SC'
            ];
        } elseif ($region == self::CENTRO_OESTE) {
            return [
                'MT',
                'MS',
                'GO',
                'DF'
            ];
        } elseif ($region == self::NORTE) {
            return [
                 'AM',
                 'RR',
                 'AP',
                 'PA',
                 'TO',
                 'RO',
                 'AC'
            ];
        } else {
            throw new \Exception('A região informada é inválida');
        }
    }

    public function calculateStateComplaintRate(string $region)
    {
        $states_rate = [];
        $states      = $this->getStates($region);
        $total_r     = $this->consumerEntity->countRegionRecords($region);
        $total       = $total_r ? (int)$total_r['total'] : 0;

        foreach ($states as $state) {
            $r = $this->consumerEntity->countStateRecords($state);
            $state_t = $r ? (int)$r['total'] : 0;
            $rate = round(($state_t/ $total) * 100, 2);
            $states_rate[$state] = $rate;
        }

        asort($states_rate);

        return $states_rate;
    }
}
