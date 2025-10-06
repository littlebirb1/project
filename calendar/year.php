<?php
// year.php - Displays entire year with 12 months
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$currentYear = date('Y');
$currentMonth = date('n');

require_once __DIR__ . '/../partials/header.php';
?>

<div class="calendar-container year-view">
    <div class="calendar-header">
        <div class="navigation">
            <a href="?view=year&year=<?= $year - 1 ?>" class="btn-nav">
                <span>&laquo;</span> <?= $year - 1 ?>
            </a>
            <h1 class="current-period"><?= $year ?></h1>
            <a href="?view=year&year=<?= $year + 1 ?>" class="btn-nav">
                <?= $year + 1 ?> <span>&raquo;</span>
            </a>
        </div>
        <div class="view-switcher">
            <a href="?view=day" class="btn-view">Day</a>
            <a href="?view=week" class="btn-view">Week</a>
            <a href="?view=month" class="btn-view">Month</a>
            <a href="?view=year&year=<?= $year ?>" class="btn-view active">Year</a>
        </div>
    </div>

    <div class="year-grid">
        <?php for ($month = 1; $month <= 12; $month++): ?>
            <?php
            $firstDay = mktime(0, 0, 0, $month, 1, $year);
            $daysInMonth = date('t', $firstDay);
            $monthName = date('F', $firstDay);
            $startDayOfWeek = date('w', $firstDay); // 0 (Sunday) to 6 (Saturday)
            ?>
            
            <div class="mini-month <?= ($month == $currentMonth && $year == $currentYear) ? 'current-month' : '' ?>">
                <div class="mini-month-header">
                    <a href="?view=month&year=<?= $year ?>&month=<?= $month ?>">
                        <?= $monthName ?>
                    </a>
                </div>
                
                <table class="mini-calendar">
                    <thead>
                        <tr>
                            <th>Su</th>
                            <th>Mo</th>
                            <th>Tu</th>
                            <th>We</th>
                            <th>Th</th>
                            <th>Fr</th>
                            <th>Sa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $day = 1;
                        $weeksInMonth = ceil(($daysInMonth + $startDayOfWeek) / 7);
                        
                        for ($week = 0; $week < $weeksInMonth; $week++):
                        ?>
                            <tr>
                                <?php for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++): ?>
                                    <?php
                                    if (($week == 0 && $dayOfWeek < $startDayOfWeek) || $day > $daysInMonth) {
                                        echo '<td class="empty"></td>';
                                    } else {
                                        $isToday = ($day == date('j') && $month == $currentMonth && $year == $currentYear);
                                        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                        $hasEvents = false; // TODO: Check if date has events
                                        ?>
                                        <td class="<?= $isToday ? 'today' : '' ?> <?= $hasEvents ? 'has-events' : '' ?>">
                                            <a href="?view=day&date=<?= $dateStr ?>">
                                                <?= $day ?>
                                            </a>
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
        <?php endfor; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>