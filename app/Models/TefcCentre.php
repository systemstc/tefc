<?php

namespace App\Models;

class TefcCentre
{
    /**
     * Official 6 TEFC Centres with their States and Covered Districts/Taluks
     */
    public static function all(): array
    {
        return [
            'surat' => [
                'code' => 'surat',
                'name' => 'Surat',
                'state' => 'Gujarat',
                'districts_label' => 'Revenue district of Surat and Navsari',
                'districts' => ['Surat', 'Navsari'],
                'email' => 'center.surat@tefc.gov.in',
            ],
            'karur' => [
                'code' => 'karur',
                'name' => 'Karur',
                'state' => 'Tamil Nadu',
                'districts_label' => 'Revenue district of Karur',
                'districts' => ['Karur'],
                'email' => 'center.karur@tefc.gov.in',
            ],
            'ichalkaranji' => [
                'code' => 'ichalkaranji',
                'name' => 'Ichalkaranji',
                'state' => 'Maharashtra',
                'districts_label' => 'Revenue district of Kolhapur',
                'districts' => ['Kolhapur'],
                'email' => 'center.ichalkaranji@tefc.gov.in',
            ],
            'jaipur' => [
                'code' => 'jaipur',
                'name' => 'Jaipur',
                'state' => 'Rajasthan',
                'districts_label' => 'Revenue district of Jaipur',
                'districts' => ['Jaipur'],
                'email' => 'center.jaipur@tefc.gov.in',
            ],
            'varanasi' => [
                'code' => 'varanasi',
                'name' => 'Varanasi',
                'state' => 'Uttar Pradesh',
                'districts_label' => 'Revenue district of Varanasi and Sant Ravidas Nagar (Bhadohi) & Revenue taluks of Mirzapur and Chunar of Mirzapur district',
                'districts' => ['Varanasi', 'Sant Ravidas Nagar (Bhadohi)', 'Mirzapur', 'Chunar'],
                'email' => 'center.varanasi@tefc.gov.in',
            ],
            'ludhiana' => [
                'code' => 'ludhiana',
                'name' => 'Ludhiana',
                'state' => 'Punjab',
                'districts_label' => 'Revenue district of Ludhiana',
                'districts' => ['Ludhiana'],
                'email' => 'center.ludhiana@tefc.gov.in',
            ],
        ];
    }

    /**
     * Find centre by code (e.g. 'surat', 'karur')
     */
    public static function find(string $code): ?array
    {
        $centres = self::all();
        $code = strtolower(trim($code));
        return $centres[$code] ?? null;
    }

    /**
     * Match a given state and district to its designated TEFC Centre.
     * Returns the centre array or null if outside jurisdiction.
     */
    public static function findCentreByLocation(?string $state, ?string $district): ?array
    {
        if (empty($state) || empty($district)) {
            return null;
        }

        $stateClean = strtolower(trim($state));
        $districtClean = strtolower(trim($district));

        foreach (self::all() as $centre) {
            if (strtolower($centre['state']) === $stateClean) {
                foreach ($centre['districts'] as $dist) {
                    $d = strtolower($dist);
                    // Match exact, contains, or fuzzy match (e.g. 'Bhadohi', 'Kolhapur', 'Chunar')
                    if ($d === $districtClean || str_contains($districtClean, $d) || str_contains($d, $districtClean)) {
                        return $centre;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get grouped list of eligible states and their districts for registration dropdowns
     */
    public static function getEligibleDistrictsGrouped(): array
    {
        $grouped = [];
        foreach (self::all() as $centre) {
            $state = $centre['state'];
            if (!isset($grouped[$state])) {
                $grouped[$state] = [];
            }
            foreach ($centre['districts'] as $dist) {
                $grouped[$state][] = [
                    'district' => $dist,
                    'centre_name' => $centre['name'],
                    'centre_code' => $centre['code'],
                ];
            }
        }
        return $grouped;
    }
}
