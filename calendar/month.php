<?php
// month.php - Displays single month with events
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

// Validation
if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }

$firstDay = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDay);
$monthName = date('F', $firstDay);
$startDayOfWeek = date('w', $firstDay);

// Calculate previous and next month
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

require_once __DIR__ . '/../partials/header.php';
?>

<div class="calendar-container month-view">
    <div class="calendar-header">
        <div class="navigation">
            <a href="?view=month&year=<?= $prevYear ?>&month=<?= $prevMonth ?>" class="btn-nav">
                <span>&laquo;</span> Previous
            </a>
            <h1 class="current-period">
                <a href="?view=year&year=<?= $year ?>"><?= $monthName ?> <?= $year ?></a>
            </h1>
            <a href="?view=month&year=<?= $nextYear ?>&month=<?= $nextMonth ?>" class="btn-nav">
                Next <span>&raquo;</span>
            </a>
        </div>
        <div class="actions">
            <a href="?view=month&year=<?= date('Y') ?>&month=<?= date('n') ?>" class="btn-today">Today</a>
            <button class="btn-create" onclick="openEventModal()">+ Create Event</button>
        </div>
        <div class="view-switcher">
            <a href="?view=day&date=<?= date('Y-m-d') ?>" class="btn-view">Day</a>
            <a href="?view=week&date=<?= date('Y-m-d') ?>" class="btn-view">Week</a>
            <a href="?view=month&year=<?= $year ?>&month=<?= $month ?>" class="btn-view active">Month</a>
            <a href="?view=year&year=<?= $year ?>" class="btn-view">Year</a>
        </div>
    </div>

    <table class="calendar-grid">
        <thead>
            <tr>
                <th>Sunday</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $day = 1;
            $weeksInMonth = ceil(($daysInMonth + $startDayOfWeek) / 7);
            $today = date('Y-m-d');
            
            for ($week = 0; $week < $weeksInMonth; $week++):
            ?>
                <tr>
                    <?php for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++): ?>
                        <?php
                        if (($week == 0 && $dayOfWeek < $startDayOfWeek) || $day > $daysInMonth) {
                            echo '<td class="empty-day"></td>';
                        } else {
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $isToday = ($dateStr == $today);
                            $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                            ?>
                            <td class="calendar-day <?= $isToday ? 'today' : '' ?> <?= $isWeekend ? 'weekend' : '' ?>" 
                                data-date="<?= $dateStr ?>">
                                <div class="day-header">
                                    <a href="?view=day&date=<?= $dateStr ?>" class="day-number">
                                        <?= $day ?>
                                    </a>
                                </div>
                                <div class="day-events" id="events-<?= $dateStr ?>">
                                    <!-- Events will be loaded here via AJAX or PHP -->
                                    <?php
                                    // TODO: Load events for this date
                                    // Example: include events from database
                                    ?>
                                </div>
                            </td>
                            <?php
                            $day++;
                        }
                        ?>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>