<?php

require __DIR__ . '/functions.php';

$text = $_GET['text'] ?? '';
$result = null;

if (isset($_GET['text']) && trim($text) !== '') {
	$result = filterWordsWithDoubleConsonants($text);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>ЛР 3. Задание 3</title>
	<link rel="stylesheet" href="/style.css">
</head>
<body>
	<?php require __DIR__ . '/../menu.php'; ?>
	<main class="content">
		<h1>Задание 3 </h1>
		<p>Удалить из текста слова, которые содержат двойные согласные.</p>

		<form method="GET">
			<textarea name="text" rows="5" cols="60" placeholder="Введите текст на русском, заканчивающийся точкой..."><?= htmlspecialchars($_GET['text'] ?? '', ENT_QUOTES) ?></textarea>
			<br><br>
			<button type="submit">Обработать</button>
		</form>

		<?php if ($result !== null) { ?>
			<?php if (!$result['success']) { ?>
				<div class="errors"><p><?= $result['message'] ?></p></div>
			<?php } else { ?>
				<div class="block">
					<h2>Исходный текст</h2>
					<p><?= htmlspecialchars($text, ENT_QUOTES) ?></p>
					
					<h2>Исправленный текст</h2>
					<p><?= htmlspecialchars($result['result'], ENT_QUOTES) ?></p>
					
					<p>Осталось слов: <?= $result['count'] ?></p>
				</div>
			<?php } ?>
		<?php } ?>
	</main>
</body>
</html>