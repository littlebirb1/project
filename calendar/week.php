<?php
// week.php - Displays single week with hourly breakdown
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$timestamp = strtotime($date);

// Get the start of the week (Sunday)
$dayOfWeek = date('w', $timestamp);
$weekStart = strtotime("-$dayOfWeek days", $timestamp);
$weekEnd = strtotime("+6 days", $weekStart);

$year = date('Y', $weekStart);
$weekNumber = date('W', $weekStart);

// Previous and next week
$prevWeekStart = strtotime("-7 days", $weekStart);
$nextWeekStart = strtotime("+7 days", $weekStart);

require_once __DIR__ . '/../partials/header.php';
?>

<div class="calendar-container week-view">
    <div class="calendar-header">
        <div class="navigation">
            <a href="?view=week&date=<?= date('Y-m-d', $prevWeekStart) ?>" class="btn-nav">
                <span>&laquo;</span> Previous
            </a>
            <h1 class="current-period">
                <a href="?view=year&year=<?= $year ?>">
                    Week <?= $weekNumber ?>, <?= $year ?>
                </a>
                <span class="date-range">
                    <?= date('M j', $weekStart) ?> - <?= date('M j', $weekEnd) ?>
                </span>
            </h1>
            <a href="?view=week&date=<?= date('Y-m-d', $nextWeekStart) ?>" class="btn-nav">
                Next <span>&raquo;</span>
            </a>
        </div>
        <div class="actions">
            <a href="?view=week&date=<?= date('Y-m-d') ?>" class="btn-today">Today</a>
            <button class="btn-create" onclick="openEventModal()">+ Create Event</button>
        </div>
        <div class="view-switcher">
            <a href="?view=day&date=<?= $date ?>" class="btn-view">Day</a>
            <a href="?view=week&date=<?= $date ?>" class="btn-view active">Week</a>
            <a href="?view=month&year=<?= date('Y', $timestamp) ?>&month=<?= date('n', $timestamp) ?>" class="btn-view">Month</a>
            <a href="?view=year&year=<?= $year ?>" class="btn-view">Year</a>
        </div>
    </div>

    <div class="week-grid">
        <div class="time-column">
            <div class="time-header">GMT+8</div>
            <?php for ($hour = 0; $hour < 24; $hour++): ?>
                <div class="time-slot">
                    <?= sprintf('%02d:00', $hour) ?>
                </div>
            <?php endfor; ?>
        </div>

        <?php
        $today = date('Y-m-d');
        for ($i = 0; $i < 7; $i++):
            $currentDay = strtotime("+$i days", $weekStart);
            $dateStr = date('Y-m-d', $currentDay);
            $dayName = date('D', $currentDay);
            $dayNumber = date('j', $currentDay);
            $isToday = ($dateStr == $today);
        ?>
            <div class="day-column <?= $isToday ? 'today' : '' ?>" data-date="<?= $dateStr ?>">
                <div class="day-header">
                    <a href="?view=day&date=<?= $dateStr ?>">
                        <span class="day-name"><?= $dayName ?></span>
                        <span class="day-number"><?= $dayNumber ?></span>
                    </a>
                </div>
                <div class="hour-slots">
                    <?php for ($hour = 0; $hour < 24; $hour++): ?>
                        <div class="hour-slot" data-hour="<?= $hour ?>">
                            <!-- Events will be positioned here -->
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>