<?php
/**
 * Проверка целого положительного числа
 */
function getPositiveInt(?string $value): ?int
{
	if ($value === null || $value === '') {
		return null;
	}
	if (!filter_var($value, FILTER_VALIDATE_INT)) {
		return null;
	}
	$number = (int)$value;
	return $number > 0 ? $number : null;
}

/**
 * ароверка веществ. числа
 */
function getFloat(?string $value): ?float
{
	if ($value === null || $value === '') {
		return null;
	}
	if (!is_numeric($value)) {
		return null;
	}
	return (float)$value;
}

$n     = getPositiveInt($_GET['n'] ?? null);
$lower = getFloat($_GET['lower'] ?? null);
$upper = getFloat($_GET['upper'] ?? null);

$hasErrors = false;
$errors    = [];

if (isset($_GET['n']) || isset($_GET['lower']) || isset($_GET['upper'])) {
	if ($n === null) {
		$hasErrors = true;
		$errors[]  = 'Размер массива n должен быть целым положительным числом.';
	}
	if ($lower === null) {
		$hasErrors = true;
		$errors[]  = 'Нижняя граница должна быть числом.';
	}
	if ($upper === null) {
		$hasErrors = true;
		$errors[]  = 'Верхняя граница должна быть числом.';
	}
	if ($lower !== null && $upper !== null && $lower > $upper) {
		$hasErrors = true;
		$errors[]  = 'Нижняя граница не может быть больше верхней.';
	}
}

$sourceArray = [];
$task1Result = null;
$task2Result = null;
$task3Result = [];

if (!$hasErrors && $n !== null && $lower !== null && $upper !== null) {
	// Генерируем исходный массив (вещественные числа)
	for ($i = 0; $i < $n; $i++) {
		$sourceArray[] = round($lower + ($upper - $lower) * (mt_rand() / mt_getrandmax()), 2);
	}

	// Пункт 1: сумма положительных
	$task1Array = $sourceArray;
	$sum = 0;
	foreach ($task1Array as $value) {
		if ($value > 0) {
			$sum += $value;
		}
	}
	$task1Result = $sum;

	// Пункт 2: произведение между max по модулю и min по модулю 
	$task2Array = $sourceArray; 

	$maxAbsIndex = 0;
	$minAbsIndex = 0;

	foreach ($task2Array as $index => $value) {
		if (abs($value) > abs($task2Array[$maxAbsIndex])) {
			$maxAbsIndex = $index;
		}
		if (abs($value) < abs($task2Array[$minAbsIndex])) {
			$minAbsIndex = $index;
		}
	}

	// определяем кто левее чтобы взять между
	$left  = min($maxAbsIndex, $minAbsIndex);
	$right = max($maxAbsIndex, $minAbsIndex);

	$product = 1;
	$countBetween = 0;
	for ($i = $left + 1; $i < $right; $i++) {
		$product *= $task2Array[$i];
		$countBetween++;
	}

	// По регламенту если между ничего нет = 1
	$task2Result = ($countBetween === 0) ? 1 : $product;

	// Пункт 3: сортировка по убыванию
	$task3Array = $sourceArray; 
	rsort($task3Array);
	$task3Result = $task3Array;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Одномерный массив</title>
	<link rel="stylesheet" href="/style.css">
</head>
<body>
	<?php require __DIR__ . '/../menu.php'; ?>

	<main class="content">
		<h1>Задание 1. Одномерный массив</h1>

		<form method="GET">
			<label>n: <input type="text" name="n" value="<?= htmlspecialchars($_GET['n'] ?? '') ?>"></label>
			<label>Нижняя граница: <input type="text" name="lower" value="<?= htmlspecialchars($_GET['lower'] ?? '') ?>"></label>
			<label>Верхняя граница: <input type="text" name="upper" value="<?= htmlspecialchars($_GET['upper'] ?? '') ?>"></label>
			<button type="submit">Сгенерировать</button>
		</form>

		<?php if ($hasErrors) { ?>
			<div class="errors">
				<?php foreach ($errors as $error) { ?>
					<p><?= $error ?></p>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if (!$hasErrors && count($sourceArray) > 0) { ?>
			<div class="block">
				<h2>Исходный массив</h2>
				<table>
					<tr>
						<?php foreach ($sourceArray as $index => $value) { ?>
							<th><?= $index + 1 ?></th>
						<?php } ?>
					</tr>
					<tr>
						<?php foreach ($sourceArray as $value) { ?>
							<td><?= $value ?></td>
						<?php } ?>
					</tr>
				</table>
			</div>

			<div class="block">
				<h2>1. Сумма положительных элементов</h2>
				<p><?= $task1Result ?></p>
			</div>

			<div class="block">
				<h2>2. Произведение между max и min по модулю</h2>
				<p><?= $task2Result ?></p>
			</div>

			<div class="block">
				<h2>3. Отсортированный по убыванию массив</h2>
				<table>
					<tr>
						<?php foreach ($task3Result as $index => $value) { ?>
							<th><?= $index + 1 ?></th>
						<?php } ?>
					</tr>
					<tr>
						<?php foreach ($task3Result as $value) { ?>
							<td><?= $value ?></td>
						<?php } ?>
					</tr>
				</table>
			</div>
		<?php } ?>
	</main>
</body>
</html>