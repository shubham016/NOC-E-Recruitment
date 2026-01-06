/**
 * Standalone Nepali Date Utilities
 * BS/AD Conversion without external dependencies
 */

(function (window) {
    "use strict";

    // Nepali calendar data (days in each month for different years)
    const nepaliCalendarData = {
        2080: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2081: [31, 31, 32, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2082: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 30],
        2083: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        2084: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        2085: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2086: [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
        2087: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        2088: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2089: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2090: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
    };

    // Base date for conversion (2080-01-01 BS = 2023-04-14 AD)
    const baseBS = { year: 2080, month: 1, day: 1 };
    const baseAD = new Date(2023, 3, 14); // April 14, 2023

    // Get total days in a BS year
    function getTotalDaysInBSYear(year) {
        if (!nepaliCalendarData[year]) return 365;
        return nepaliCalendarData[year].reduce((a, b) => a + b, 0);
    }

    // Get days in a specific BS month
    function getDaysInBSMonth(year, month) {
        if (!nepaliCalendarData[year]) return 30;
        return nepaliCalendarData[year][month - 1] || 30;
    }

    // Convert AD to BS
    window.adToBS = function (adDate) {
        try {
            const ad = new Date(adDate);
            if (isNaN(ad.getTime())) return "";

            // Calculate days difference from base date
            const diffTime = ad - baseAD;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            let bsYear = baseBS.year;
            let bsMonth = baseBS.month;
            let bsDay = baseBS.day;
            let remainingDays = diffDays;

            // Handle negative days (dates before base date)
            if (remainingDays < 0) {
                while (remainingDays < 0) {
                    bsDay--;
                    if (bsDay < 1) {
                        bsMonth--;
                        if (bsMonth < 1) {
                            bsYear--;
                            bsMonth = 12;
                        }
                        bsDay = getDaysInBSMonth(bsYear, bsMonth);
                    }
                    remainingDays++;
                }
            } else {
                // Handle positive days
                remainingDays += bsDay;

                while (remainingDays > getDaysInBSMonth(bsYear, bsMonth)) {
                    remainingDays -= getDaysInBSMonth(bsYear, bsMonth);
                    bsMonth++;
                    if (bsMonth > 12) {
                        bsYear++;
                        bsMonth = 1;
                    }
                }
                bsDay = remainingDays;
            }

            const year = bsYear;
            const month = String(bsMonth).padStart(2, "0");
            const day = String(bsDay).padStart(2, "0");

            return `${year}-${month}-${day}`;
        } catch (error) {
            console.error("AD to BS conversion error:", error);
            return "";
        }
    };

    // Convert BS to AD
    window.bsToAD = function (bsDate) {
        try {
            const [year, month, day] = bsDate.split("-").map(Number);

            if (!year || !month || !day) return "";

            // Calculate total days from base BS date
            let totalDays = 0;

            // Add days for complete years
            for (let y = baseBS.year; y < year; y++) {
                totalDays += getTotalDaysInBSYear(y);
            }

            // Add days for complete months in current year
            for (let m = 1; m < month; m++) {
                totalDays += getDaysInBSMonth(year, m);
            }

            // Add remaining days
            totalDays += day - baseBS.day;

            // Calculate AD date
            const adDate = new Date(baseAD);
            adDate.setDate(adDate.getDate() + totalDays);

            const adYear = adDate.getFullYear();
            const adMonth = String(adDate.getMonth() + 1).padStart(2, "0");
            const adDay = String(adDate.getDate()).padStart(2, "0");

            return `${adYear}-${adMonth}-${adDay}`;
        } catch (error) {
            console.error("BS to AD conversion error:", error);
            return "";
        }
    };

    // Format date for display
    window.formatDisplayDate = function (dateString) {
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        } catch (error) {
            return dateString;
        }
    };

    // Nepali month names
    window.nepaliMonths = [
        "Baishakh",
        "Jestha",
        "Ashadh",
        "Shrawan",
        "Bhadra",
        "Ashwin",
        "Kartik",
        "Mangsir",
        "Poush",
        "Magh",
        "Falgun",
        "Chaitra",
    ];

    window.nepaliMonthsNepali = [
        "बैशाख",
        "जेठ",
        "असार",
        "श्रावण",
        "भाद्र",
        "आश्विन",
        "कार्तिक",
        "मंसिर",
        "पौष",
        "माघ",
        "फाल्गुण",
        "चैत्र",
    ];

    console.log("✅ Nepali Date Utils loaded successfully");
})(window);
