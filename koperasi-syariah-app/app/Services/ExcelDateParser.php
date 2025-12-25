<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class ExcelDateParser
{
    /**
     * Parse date from Excel format (numeric or string)
     */
    public static function parseDate($dateValue)
    {
        if (empty($dateValue)) {
            return null;
        }

        try {
            // If it's already in string format
            if (is_string($dateValue) && strpos($dateValue, '-') !== false) {
                return date('Y-m-d', strtotime($dateValue));
            }

            // If it's numeric (Excel date format)
            if (is_numeric($dateValue)) {
                try {
                    $phpDate = Date::excelToDateTimeObject($dateValue);
                    return $phpDate->format('Y-m-d');
                } catch (\Exception $e) {
                    // Fallback to Carbon
                    return Carbon::createFromFormat('Y-m-d', '1900-01-01')
                                  ->addDays($dateValue - 2)
                                  ->format('Y-m-d');
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}