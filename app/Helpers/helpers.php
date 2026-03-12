<?php

if (!function_exists('adToBS')) {
    /**
     * Convert AD (English) date to BS (Nepali) date
     *
     * @param string|DateTime $adDate AD date in YYYY-MM-DD format or DateTime object
     * @return string BS date in YYYY-MM-DD format
     */
    function adToBS($adDate)
    {
        // Reference point: 2000-01-01 AD = 2056-09-17 BS
        $referenceAD = new DateTime('2000-01-01');
        $referenceBS = ['year' => 2056, 'month' => 9, 'day' => 17];

        // Days in each BS month (approximation based on BS calendar)
        $bsMonthDays = [
            [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30], // 2056
            [31, 31, 32, 32, 31, 30, 30, 30, 29, 29, 30, 31], // 2057
            [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31], // 2058
            [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30], // 2059
            [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30], // 2060
            [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31], // 2061
            [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31], // 2062
            [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30], // 2063
            [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30], // 2064
            [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31], // 2065
            [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31], // 2066
            [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30], // 2067
            [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30], // 2068
            [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31], // 2069
            [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30], // 2070
            [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30], // 2071
            [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30], // 2072
            [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31], // 2073
            [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30], // 2074
            [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30], // 2075
            [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30], // 2076
            [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31], // 2077
            [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30], // 2078
            [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30], // 2079
            [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30], // 2080
            [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31], // 2081
            [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30], // 2082
            [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30], // 2083
        ];

        try {
            // Convert string to DateTime object if needed
            if (is_string($adDate)) {
                $adDate = new DateTime($adDate);
            }

            // Calculate days difference from reference
            $daysDiff = $referenceAD->diff($adDate)->days;
            if ($adDate < $referenceAD) {
                $daysDiff = -$daysDiff;
            }

            // Start from reference BS date
            $bsYear = $referenceBS['year'];
            $bsMonth = $referenceBS['month'];
            $bsDay = $referenceBS['day'];

            // Add/subtract days
            if ($daysDiff >= 0) {
                // Moving forward
                while ($daysDiff > 0) {
                    $yearIndex = $bsYear - 2056;
                    if (!isset($bsMonthDays[$yearIndex])) {
                        // Fallback to last known year pattern
                        $yearIndex = count($bsMonthDays) - 1;
                    }

                    $daysInCurrentMonth = $bsMonthDays[$yearIndex][$bsMonth - 1];
                    $daysLeftInMonth = $daysInCurrentMonth - $bsDay;

                    if ($daysDiff > $daysLeftInMonth) {
                        $daysDiff -= ($daysLeftInMonth + 1);
                        $bsDay = 1;
                        $bsMonth++;

                        if ($bsMonth > 12) {
                            $bsMonth = 1;
                            $bsYear++;
                        }
                    } else {
                        $bsDay += $daysDiff;
                        $daysDiff = 0;
                    }
                }
            }

            return sprintf('%04d-%02d-%02d', $bsYear, $bsMonth, $bsDay);

        } catch (Exception $e) {
            return '';
        }
    }
}

if (!function_exists('formatNepaliDate')) {
    /**
     * Format datetime with Nepali date
     *
     * @param string|DateTime $datetime
     * @param bool $includeTime
     * @return string
     */
    function formatNepaliDate($datetime, $includeTime = true)
    {
        if (!$datetime) {
            return 'N/A';
        }

        try {
            if (is_string($datetime)) {
                $datetime = new DateTime($datetime);
            }

            $bsDate = adToBS($datetime->format('Y-m-d'));

            if ($includeTime) {
                return $bsDate . ' BS, ' . $datetime->format('h:i A');
            }

            return $bsDate . ' BS';

        } catch (Exception $e) {
            return 'N/A';
        }
    }
}
