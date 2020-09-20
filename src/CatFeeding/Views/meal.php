<?php
include "../../Shared/Views/View.php";
$mealId = intval($_REQUEST['Id']);
$meal = get('select meals.*,
       (meals.start_weight - meals.end_weight) / meals.daily_demand_weight * 100 as daily_demand_percentage
from (
         select m.id,
                f.name,
                m.start,
                m.start_weight,
                m.end,
                m.end_weight,
                (
                    select d.weight
                    from daily_demand d
                    where d.cat_id = m.cat_id
                      and d.food_id = m.food_id
                    order by d.timestamp desc
                    limit 1
                ) as daily_demand_weight
         from meal m
                  join food f on f.id = m.food_id
         where m.id = ?
     ) meals', [$mealId]);

if ($meal === false) {
    showFinalWarning('Nie znaleziono posiłku.');
}
?>
    <h1>Posiłek</h1>
    <p><?= $meal['name'] ?></p>
    <p><?= $meal['start_weight'] ?> - <?= $meal['end_weight'] ?>
        = <?= showInt($meal['start_weight'] - $meal['end_weight']) ?> g
        = <?= showInt($meal['daily_demand_percentage']) ?> %</p>
    <p><?= $meal['start'] ?> - <?= $meal['end'] ?></p>
    <form action="../UseCases/RemoveMealUseCase.php" method="post">
        <input type="hidden" name="Id" value="<?= $mealId ?>">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Usuń</button>
        </div>
    </form>
<?php
include '../../Shared/Views/Footer.php';