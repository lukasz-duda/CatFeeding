<?php
include '../../Shared/UseCases/UseCase.php';

$catId = intval($_REQUEST['CatId']);
$foodId = intval($_REQUEST['FoodId']);
$weight = intval($_REQUEST['Weight']);
$medicineApplied = $_REQUEST['MedicineApplied'] != null;

if (notValidId($catId)) {
    showFinalWarning('Nie wybrano kota');
}

if (notValidId($foodId)) {
    showFinalWarning('Nie wybrano pokarmu.');
}

if (notValidValue($weight)) {
    showFinalWarning('Nie podano wagi.');
}

$saveMeal = pdo()->prepare('INSERT INTO meal (cat_id, food_id, start, start_weight) values (?, ?, ?, ?)');
$mealSaved = $saveMeal->execute([$catId, $foodId, date('Y-m-d H:i:s'), $weight]);

if ($mealSaved) {
    showInfo('Posiłek rozpoczęty.');

    if ($medicineApplied) {
        $applyMedicine = pdo()->prepare('insert into medicine (cat_id, date) values (?, ?)');
        $applyMedicineSuccess = $applyMedicine->execute([$catId, date('Y-m-d')]);

        if ($applyMedicineSuccess) {
            showInfo('Lek podany');
        } else {
            showError('Nie udało się podać leku');
        }
    }
} else {
    showError('Nie udało się rozpocząć posiłku!');
    showStatementError($saveMeal);
}

include '../../Shared/Views/Footer.php';