<?php
// day.php - Displays single day with hourly breakdown
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$timestamp = strtotime($date);

$year = date('Y', $timestamp);
$month = date('n', $timestamp);
$dayName = date('l', $timestamp);
$monthName = date('F', $timestamp);
$dayNumber = date('j', $timestamp);

// Previous and next day
$prevDay = date('Y-m-d', strtotime("-1 day", $timestamp));
$nextDay = date('Y-m-d', strtotime("+1 day", $timestamp));

require_once __DIR__ . '/../partials/header.php';
?>

<div class="calendar-container day-view">
    <div class="calendar-header">
        <div class="navigation">
            <a href="?view=day&date=<?= $prevDay ?>" class="btn-nav">
                <span>&laquo;</span> Previous
            </a>
            <h1 class="current-period">
                <a href="?view=year&year=<?= $year ?>">
                    <?= $dayName ?>, <?= $monthName ?> <?= $dayNumber ?>, <?= $year ?>
                </a>
            </h1>
            <a href="?view=day&date=<?= $nextDay ?>" class="btn-nav">
                Next <span>&raquo;</span>
            </a>
        </div>
        <div class="actions">
            <a href="?view=day&date=<?= date('Y-m-d') ?>" class="btn-today">Today</a>
            <button class="btn-create" onclick="openEventModal('<?= $date ?>')">+ Create Event</button>
        </div>
        <div class="view-switcher">
            <a href="?view=day&date=<?= $date ?>" class="btn-view active">Day</a>
            <a href="?view=week&date=<?= $date ?>" class="btn-view">Week</a>
            <a href="?view=month&year=<?= $year ?>&month=<?= $month ?>" class="btn-view">Month</a>
            <a href="?view=year&year=<?= $year ?>" class="btn-view">Year</a>
        </div>
    </div>

    <div class="day-schedule">
        <div class="time-column">
            <?php for ($hour = 0; $hour < 24; $hour++): ?>
                <div class="time-slot">
                    <span class="time-label"><?= sprintf('%02d:00', $hour) ?></span>
                </div>
            <?php endfor; ?>
        </div>

        <div class="events-column" data-date="<?= $date ?>">
            <?php for ($hour = 0; $hour < 24; $hour++): ?>
                <div class="hour-slot" data-hour="<?= $hour ?>" onclick="createEventAt('<?= $date ?>', <?= $hour ?>)">
                    <div class="hour-events" id="hour-<?= $hour ?>">
                        <!-- Events will be loaded here -->
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>