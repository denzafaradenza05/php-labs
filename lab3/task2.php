<?php
require __DIR__ . '/functions.php';

$text  = $_GET['text'] ?? '';
$start = isset($_GET['start']) ? (int)$_GET['start'] : 1;
$end   = isset($_GET['end']) ? (int)$_GET['end'] : 1;

$result = null;
$errors = [];

if (isset($_GET['text'])) {
	$trimmedText = trim($text);

	// 1. Проверка на пустоту
	if ($trimmedText === '') {
		$errors[] = 'Строка не может быть пустой.';
	} else {
		// Проверка на недопустимые символы (разрешены только буквы, пробелы и дефисы)
		if (!isValidText($trimmedText)) {
			$errors[] = 'Строка должна содержать только русские и английские буквы, пробелы или дефисы. Цифры и спецсимволы запрещены.';
		} else {
			// Если проверки пройдены — сортируем
			$result = sortStringSlice($trimmedText, $start, $end);
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>ЛР 3. Задание 2</title>
	<link rel="stylesheet" href="/style.css">
</head>
<body>
	<?php require __DIR__ . '/../menu.php'; ?>
	<main class="content">
		<h1>Задание 2</h1>
		<p>Отсортировать символы по алфавиту с заданных начальной и конечной позиции.</p>

		<form method="GET">
			<label>Строка: <input type="text" name="text" size="50" value="<?= htmlspecialchars($_GET['text'] ?? '', ENT_QUOTES) ?>"></label><br><br>
			<label>Начальная позиция: <input type="number" name="start" value="<?= $start ?>"></label>
			<label>Конечная позиция: <input type="number" name="end" value="<?= $end ?>"></label>
			<br><br>
			<button type="submit">Сортировать</button>
		</form>

		<?php if (!empty($errors)) { ?>
			<div class="errors">
				<?php foreach ($errors as $error) { ?>
					<p><?= $error ?></p>
				<?php } ?>
			</div>
		<?php } ?>

		<!-- вывод результатов -->
		<?php if ($result !== null) { ?>
			<?php if (!$result['success']) { ?>
				<div class="errors"><p><?= $result['message'] ?></p></div>
			<?php } else { ?>
				<div class="block">
					<h2>Исходная строка</h2>
					<p><?= htmlspecialchars($text, ENT_QUOTES) ?></p>

					<h2>Отсортированный фрагмент</h2>
					<p><?= htmlspecialchars($result['slice'], ENT_QUOTES) ?></p>

					<h2>Результат</h2>
					<p><?= htmlspecialchars($result['result'], ENT_QUOTES) ?></p>
				</div>
			<?php } ?>
		<?php } ?>
	</main>
</body>
</html>