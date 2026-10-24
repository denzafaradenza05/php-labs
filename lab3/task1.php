<?php

require __DIR__ . '/functions.php';

$text = $_GET['text'] ?? '';
$result = null;

if (isset($_GET['text']) && trim($text) !== '') {
	$result = [
		'original' => $text,
		'fixed'    => makeAllLowercase($text),
		'words'    => countWords($text),
		'sentences'=> countSentences($text)
	];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>ЛР 3. Задание 1</title>
	<link rel="stylesheet" href="/style.css">
</head>
<body>
	<?php require __DIR__ . '/../menu.php'; ?>
	<main class="content">
		<h1>Задание 1 </h1>
		<p>Заменить все заглавные буквы на строчные. Вычислить количество слов и предложений.</p>

		<form method="GET">
			<textarea name="text" rows="5" cols="60" placeholder="Введите текст..."><?= htmlspecialchars($_GET['text'] ?? '', ENT_QUOTES) ?></textarea>
			<br><br>
			<button type="submit">Обработать</button>
		</form>

		<?php if ($result !== null) { ?>
			<div class="block">
				<h2>Исходный текст</h2>
				<p><?= htmlspecialchars($result['original'], ENT_QUOTES) ?></p>
				
				<h2>Исправленный текст</h2>
				<p><?= htmlspecialchars($result['fixed'], ENT_QUOTES) ?></p>

				<h2>Статистика</h2>
				<ul>
					<li>Слов: <?= $result['words'] ?></li>
					<li>Предложений: <?= $result['sentences'] ?></li>
				</ul>
			</div>
		<?php } ?>
	</main>
</body>
</html>