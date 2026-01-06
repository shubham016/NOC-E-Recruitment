/**
 * Simple Nepali Date Picker (jQuery Plugin)
 */

(function ($) {
    "use strict";

    $.fn.simpleNepaliDatePicker = function (options) {
        const settings = $.extend(
            {
                dateFormat: "YYYY-MM-DD",
                onChange: function () {},
            },
            options
        );

        return this.each(function () {
            const $input = $(this);
            const pickerId =
                "picker_" + Math.random().toString(36).substr(2, 9);

            // Make input readonly
            $input.attr("readonly", true);
            $input.css("cursor", "pointer");

            // Create picker HTML
            const $picker = $("<div>", {
                id: pickerId,
                class: "simple-nepali-picker",
                css: {
                    display: "none",
                    position: "absolute",
                    zIndex: 9999,
                    backgroundColor: "white",
                    border: "1px solid #ddd",
                    borderRadius: "8px",
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                    padding: "15px",
                    minWidth: "280px",
                },
            });

            // Get current BS date or default
            let currentBS = { year: 2082, month: 11, day: 15 };
            if ($input.val()) {
                const parts = $input.val().split("-");
                currentBS = {
                    year: parseInt(parts[0]) || 2082,
                    month: parseInt(parts[1]) || 11,
                    day: parseInt(parts[2]) || 15,
                };
            }

            function renderPicker() {
                const daysInMonth = [
                    31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 30,
                ];
                const maxDay = daysInMonth[currentBS.month - 1];

                let html = `
                    <div style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center;">
                        <select class="year-select" style="flex: 1; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                            ${Array.from({ length: 11 }, (_, i) => 2080 + i)
                                .map(
                                    (y) =>
                                        `<option value="${y}" ${
                                            y === currentBS.year
                                                ? "selected"
                                                : ""
                                        }>${y}</option>`
                                )
                                .join("")}
                        </select>
                        <select class="month-select" style="flex: 1; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                            ${window.nepaliMonths
                                .map(
                                    (m, i) =>
                                        `<option value="${i + 1}" ${
                                            i + 1 === currentBS.month
                                                ? "selected"
                                                : ""
                                        }>${m}</option>`
                                )
                                .join("")}
                        </select>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <select class="day-select" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            ${Array.from({ length: maxDay }, (_, i) => i + 1)
                                .map(
                                    (d) =>
                                        `<option value="${d}" ${
                                            d === currentBS.day
                                                ? "selected"
                                                : ""
                                        }>${d}</option>`
                                )
                                .join("")}
                        </select>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn-today" style="flex: 1; padding: 8px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">Today</button>
                        <button class="btn-close" style="flex: 1; padding: 8px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer;">Close</button>
                    </div>
                `;

                $picker.html(html);

                // Event handlers
                $picker
                    .find(".year-select, .month-select")
                    .on("change", function () {
                        currentBS.year = parseInt(
                            $picker.find(".year-select").val()
                        );
                        currentBS.month = parseInt(
                            $picker.find(".month-select").val()
                        );
                        currentBS.day = 1;
                        renderPicker();
                    });

                $picker.find(".day-select").on("change", function () {
                    currentBS.day = parseInt($(this).val());
                    updateInput();
                });

                $picker.find(".btn-today").on("click", function () {
                    const today = new Date();
                    const bsToday = window.adToBS(
                        today.toISOString().split("T")[0]
                    );
                    const parts = bsToday.split("-");
                    currentBS = {
                        year: parseInt(parts[0]),
                        month: parseInt(parts[1]),
                        day: parseInt(parts[2]),
                    };
                    renderPicker();
                    updateInput();
                });

                $picker.find(".btn-close").on("click", function () {
                    $picker.hide();
                });
            }

            function updateInput() {
                const bsDate = `${currentBS.year}-${String(
                    currentBS.month
                ).padStart(2, "0")}-${String(currentBS.day).padStart(2, "0")}`;
                $input.val(bsDate);
                settings.onChange();
                $picker.hide();
            }

            // Show picker on input click
            $input.on("click", function (e) {
                e.preventDefault();

                // Position picker
                const offset = $input.offset();
                $picker.css({
                    top: offset.top + $input.outerHeight() + 5,
                    left: offset.left,
                });

                renderPicker();
                $picker.show();
            });

            // Close picker on outside click
            $(document).on("click", function (e) {
                if (
                    !$(e.target).closest("#" + pickerId).length &&
                    !$(e.target).is($input)
                ) {
                    $picker.hide();
                }
            });

            // Append picker to body
            $("body").append($picker);
        });
    };

    console.log("✅ Simple Nepali Date Picker loaded successfully");
})(jQuery);
