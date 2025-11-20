<?php

namespace App\Helpers;

class AzollaSystemCalculator
{
    /**
     * Calculate the number of Azolla units needed based on herd size
     *  Azolla unit per 25 sheep
     *
     * @param int $herdSize
     * @return int
     */
    public static function calculateAzollaUnits(int $herdSize): int
    {
        if ($herdSize <= 0) {
            return 0;
        }
        
        return (int) ceil($herdSize / 25);
    }

    /**
     * Calculate the agriculture systems needed based on herd size
     * System Types:
     * - Azolla 20 Unit System:  1–20 sheep
     * - Azolla 50 Unit System:  21–50 sheep  
     * - Azolla 100 Unit System:  51–100 sheep
     *
     * @param int $herdSize
     * @return array
     */
    public static function calculateSystemsNeeded(int $herdSize): array
    {
        if ($herdSize <= 0) {
            return [];
        }

        $systems = [];
        $remainingSheep = $herdSize;

        while ($remainingSheep > 0) {
            if ($remainingSheep >= 51) {
                // Use Azolla 100 System (supports 51-100 sheep)
                $sheepCovered = min(100, $remainingSheep);
                $systems[] = [
                    'system_name' => 'Azolla 100 Unit System',
                    'capacity_min' => 51,
                    'capacity_max' => 100,
                    'sheep_covered' => $sheepCovered,
                    'system_type' => 'azolla_100'
                ];
                $remainingSheep -= $sheepCovered;
            } elseif ($remainingSheep >= 21) {
                // Use Azolla 50 System (supports 21-50 sheep)
                $sheepCovered = min(50, $remainingSheep);
                $systems[] = [
                    'system_name' => 'Azolla 50 Unit System',
                    'capacity_min' => 21,
                    'capacity_max' => 50,
                    'sheep_covered' => $sheepCovered,
                    'system_type' => 'azolla_50'
                ];
                $remainingSheep -= $sheepCovered;
            } else {
                // Use Azolla 20 System (supports 1-20 sheep)
                $systems[] = [
                    'system_name' => 'Azolla 20 Unit System',
                    'capacity_min' => 1,
                    'capacity_max' => 20,
                    'sheep_covered' => $remainingSheep,
                    'system_type' => 'azolla_20'
                ];
                $remainingSheep = 0;
            }
        }

        return $systems;
    }

    /**
     * Get system configuration by type
     *
     * @param string $systemType
     * @return array|null
     */
    public static function getSystemConfiguration(string $systemType): ?array
    {
        $configurations = [
            'azolla_20' => [
                'name' => 'Azolla 20 Unit System',
                'capacity_min' => 1,
                'capacity_max' => 20,
                'description' => 'Supports 1-20 sheep'
            ],
            'azolla_50' => [
                'name' => 'Azolla 50 Unit System',
                'capacity_min' => 21,
                'capacity_max' => 50,
                'description' => 'Supports 21-50 sheep'
            ],
            'azolla_100' => [
                'name' => 'Azolla 100 Unit System',
                'capacity_min' => 51,
                'capacity_max' => 100,
                'description' => 'Supports 51-100 sheep'
            ]
        ];

        return $configurations[$systemType] ?? null;
    }

    /**
     * Validate herd size and return any business rule violations
     *
     * @param int $herdSize
     * @return array
     */
    public static function validateHerdSize(int $herdSize): array
    {
        $errors = [];

        if ($herdSize <= 0) {
            $errors[] = 'Herd size must be greater than 0';
        }

        if ($herdSize > 10000) { // Reasonable upper limit
            $errors[] = 'Herd size seems unusually large. Please verify the number.';
        }

        return $errors;
    }

    /**
     * Generate a summary report of the calculation
     *
     * @param int $herdSize
     * @return array
     */
    public static function generateCalculationSummary(int $herdSize): array
    {
        $azollaUnits = self::calculateAzollaUnits($herdSize);
        $systems = self::calculateSystemsNeeded($herdSize);
        $errors = self::validateHerdSize($herdSize);

        return [
            'herd_size' => $herdSize,
            'azolla_units' => $azollaUnits,
            'systems' => $systems,
            'total_systems' => count($systems),
            'validation_errors' => $errors,
            'is_valid' => empty($errors)
        ];
    }
}