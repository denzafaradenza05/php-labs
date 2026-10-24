<?php
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
// проверка веществ. чисел
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
$m     = getPositiveInt($_GET['m'] ?? null);
$lower = getFloat($_GET['lower'] ?? null);
$upper = getFloat($_GET['upper'] ?? null);

$hasErrors = false;
$errors    = [];

if (isset($_GET['n']) || isset($_GET['m']) || isset($_GET['lower']) || isset($_GET['upper'])) {
	if ($n === null) {
		$hasErrors = true;
		$errors[]  = 'Количество строк n должно быть целым положительным.';
	}
	if ($m === null) {
		$hasErrors = true;
		$errors[]  = 'Количество столбцов m должно быть целым положительным.';
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

$sourceMatrix = [];
$task1Matrix  = [];
$task2Matrix  = [];
$task3Matrix  = [];
$task4Matrix  = [];

if (!$hasErrors && $n !== null && $m !== null && $lower !== null && $upper !== null) {
	// Генерируем исходную матрицу (вещественные числа)
	for ($i = 0; $i < $n; $i++) {
		for ($j = 0; $j < $m; $j++) {
			$sourceMatrix[$i][$j] = round($lower + ($upper - $lower) * (mt_rand() / mt_getrandmax()), 2);
		}
	}

	//Пункт 1: в каждом столбце min по модулю - 0
	$task1Matrix = $sourceMatrix; // копия
	for ($j = 0; $j < $m; $j++) {
		$minAbsIndex = 0;
		for ($i = 1; $i < $n; $i++) {
			if (abs($task1Matrix[$i][$j]) < abs($task1Matrix[$minAbsIndex][$j])) {
				$minAbsIndex = $i;
			}
		}
		$task1Matrix[$minAbsIndex][$j] = 0;
	}

	// Пункт 2: после строки с минимальным значением - строка нулей
	$task2Matrix = $sourceMatrix; // копия

	// ищем минимальное значение
	$minValue = $sourceMatrix[0][0];
	foreach ($sourceMatrix as $row) {
		foreach ($row as $value) {
			if ($value < $minValue) {
				$minValue = $value;
			}
		}
	}

	// идем с конца, чтобы вставка не сбивала индексы
	for ($i = count($task2Matrix) - 1; $i >= 0; $i--) {
		$rowHasMin = false;
		foreach ($task2Matrix[$i] as $value) {
			if ($value === $minValue) {
				$rowHasMin = true;
				break;
			}
		}

		if ($rowHasMin) {
			$zeroRow = array_fill(0, $m, 0); //создает массив и заполняет его одинаковыми значениями
			array_splice($task2Matrix, $i + 1, 0, [$zeroRow]); //вырезать и вставить элементы в массив 
		}
	}

	// Пункт 3: удалить столбцы, где первый > последнего
	$task3Matrix = $sourceMatrix;
	$deleteColumns = [];

	for ($j = 0; $j < $m; $j++) {
		$firstValue = $task3Matrix[0][$j];
		$lastValue  = $task3Matrix[$n - 1][$j];
		if ($firstValue > $lastValue) {
			$deleteColumns[] = $j;
		}
	}

	// удаляем столбцы с конца, чтоб не сбить индексы
	rsort($deleteColumns);
	foreach ($deleteColumns as $col) {
		foreach ($task3Matrix as &$row) {
			array_splice($row, $col, 1);
		}
		unset($row);
	}

	// Пункт 4: поменять первый и последний столбцы
	$task4Matrix = $sourceMatrix;
	for ($i = 0; $i < $n; $i++) {
		$temp = $task4Matrix[$i][0];
		$task4Matrix[$i][0] = $task4Matrix[$i][$m - 1];
		$task4Matrix[$i][$m - 1] = $temp;
	}
}

/**
 * вывод матрицы в виде хтмл таблицы
 */
function renderMatrix(array $matrix, string $title): void
{
	?>
	<div class="block">
		<h2><?= $title ?></h2>
		<table class="matrix-table">
			<?php foreach ($matrix as $row) { ?>
				<tr>
					<?php foreach ($row as $value) { ?>
						<td><?= $value ?></td>
					<?php } ?>
				</tr>
			<?php } ?>
		</table>
	</div>
	<?php
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Матрица</title>
	<link rel="stylesheet" href="/style.css">
</head>
<body>
	<?php require __DIR__ . '/../menu.php'; ?>

	<main class="content">
		<h1>Задание 2. Матрица</h1>

		<form method="GET">
			<label>n (строки): <input type="text" name="n" value="<?= htmlspecialchars($_GET['n'] ?? '', ENT_QUOTES) ?>"></label>
			<label>m (столбцы): <input type="text" name="m" value="<?= htmlspecialchars($_GET['m'] ?? '', ENT_QUOTES) ?>"></label>
			<label>Нижняя граница: <input type="text" name="lower" value="<?= htmlspecialchars($_GET['lower'] ?? '', ENT_QUOTES) ?>"></label>
			<label>Верхняя граница: <input type="text" name="upper" value="<?= htmlspecialchars($_GET['upper'] ?? '', ENT_QUOTES) ?>"></label>
			<button type="submit">Сгенерировать</button>
		</form>

		<?php if ($hasErrors) { ?>
			<div class="errors">
				<?php foreach ($errors as $error) { ?>
					<p><?= $error ?></p>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if (!$hasErrors && count($sourceMatrix) > 0) { ?>
			<?php renderMatrix($sourceMatrix, 'Исходная матрица'); ?>
			<?php renderMatrix($task1Matrix, '1. В каждом столбце минимальный по модулю элемент заменён на 0'); ?>
			<?php renderMatrix($task2Matrix, '2. После каждой строки с минимальным значением вставлена строка из нулей'); ?>
			<?php renderMatrix($task3Matrix, '3. Удалены столбцы, где первый элемент больше последнего'); ?>
			<?php renderMatrix($task4Matrix, '4. Поменяны местами первый и последний столбцы'); ?>
		<?php } ?>
	</main>
</body>
</html>