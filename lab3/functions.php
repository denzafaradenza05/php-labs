<?php
// ЗАДАНИЕ 1
function makeAllLowercase(string $text): string 
{
    // Переводим все английские буквы в нижний регистр
    $text = strtolower($text);
    
    // Создаем массивы для ручной замены русских букв
    $ruUpper = ['А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'];
    $ruLower = ['а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я'];
    
    // Заменяем заглавные русские на строчные
    return str_replace($ruUpper, $ruLower, $text);
}

function countSentences(string $text): int
{
	$text = trim($text);
	if ($text === '') {
		return 0;
	}
	$count = substr_count($text, '.'); // сколько раз символ встречается в тексте
	if (substr($text, -1) !== '.') {
		$count++;
	}
	return $count;
}

function countWords(string $text): int
{
	$text = trim($text);
	if ($text === '') {
		return 0;
	}
	$text = str_replace(['.', ',', '!', '?', ';', ':', '-'], ' ', $text);
	$words = explode(' ', $text); // берет строку и разбивает её на массив по какому-то разделителю
	$count = 0;
	foreach ($words as $word) {
		if (trim($word) !== '') {
			$count++;
		}
	}
	return $count;
}

// ЗАДАНИЕ 2 

function sortStringSlice(string $text, int $start, int $end): array
{
	// Разбиваем исходную строку на массив символов
	$chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
	$length = count($chars);

	if ($start < 1 || $end > $length || $start > $end) {
		return [
			'success' => false,
			'message' => "Ошибка: неверно указаны позиции. Длина строки: $length. Позиции должны быть от 1 до $length, и начало не должно быть больше конца."
		];
	}

	$startIndex = $start - 1;
	$endIndex   = $end - 1;
	$sliceLength = $end - $start + 1;

	// Вырезаем нужный фрагмент из массива символов
	$sliceChars = array_slice($chars, $startIndex, $sliceLength);
	$sliceStr = implode('', $sliceChars);

	// Алфавит для сортировки (по порядку)
	$alphabet = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz0123456789';
	$alphabetChars = preg_split('//u', $alphabet, -1, PREG_SPLIT_NO_EMPTY);

	// Сортируем вырезанный фрагмент
	usort($sliceChars, function ($a, $b) use ($alphabetChars) {
		// Приводим к нижнему регистру вручную (просто заменяем большие на маленькие)
		$ruUpper = ['А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'];
		$ruLower = ['а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я'];
		
		$lowerA = str_replace($ruUpper, $ruLower, $a);
		$lowerB = str_replace($ruUpper, $ruLower, $b);

		// ищем позицию в алфавите
		$posA = array_search($lowerA, $alphabetChars);
		$posB = array_search($lowerB, $alphabetChars);

		if ($posA === false) $posA = 999;
		if ($posB === false) $posB = 999;

		if ($posA === $posB) {
			return strcmp($a, $b); //для сравнения двух строк 
		}
		return $posA <=> $posB;
	});


	// Собираем итоговую строку
	$resultChars = array_merge(
		array_slice($chars, 0, $startIndex),
		$sliceChars,
		array_slice($chars, $endIndex + 1)
	);
	$resultText = implode('', $resultChars);

	return [
		'success' => true,
		'result'  => $resultText,
		'slice'   => $sliceStr
	];
}

function isValidText(string $text): bool
{
	// разбиваем строку на символы
	$chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
	
	// Разрешенные символы 
	$allowedChars = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ -';
	
	foreach ($chars as $char) {
		// если символа нет в разрешенном списке то это мусор
		if (strpos($allowedChars, $char) === false) {
			return false;
		}
	}
	
	return true;
}

// ЗАДАНИЕ 3 

function hasDoubleConsonants(string $word): bool
{
	// Список согласных букв в нижнем регистре
	$consonants = ['б','в','г','д','ж','з','й','к','л','м','н','п','р','с','т','ф','х','ц','ч','ш','щ'];
	
	// Разбиваем слово на массив символов
	$letters = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
	$length = count($letters);

	if ($length < 2) {
		return false;
	}

	for ($i = 0; $i < $length - 1; $i++) {
		$char1 = $letters[$i];
		$char2 = $letters[$i + 1];

		// Делаем буквы строчными вручную (если это заглавная)
		$char1 = str_replace(['Б','В','Г','Д','Ж','З','Й','К','Л','М','Н','П','Р','С','Т','Ф','Х','Ц','Ч','Ш','Щ'], $consonants, $char1);
		$char2 = str_replace(['Б','В','Г','Д','Ж','З','Й','К','Л','М','Н','П','Р','С','Т','Ф','Х','Ц','Ч','Ш','Щ'], $consonants, $char2);

		// Если обе буквы — согласные и они одинаковые
		if (in_array($char1, $consonants) && in_array($char2, $consonants) && $char1 === $char2) {
			return true;
		}
	}

	return false;
}

function filterWordsWithDoubleConsonants(string $text): array
{
	$text = trim($text);

	if (substr($text, -1) !== '.') {
		return [
			'success' => false,
			'message' => 'Ошибка: текст должен заканчиваться точкой.'
		];
	}

	// Убираем точку в конце
	$textWithoutDot = substr($text, 0, -1);
	
	// Разбиваем по пробелам
	$words = explode(' ', $textWithoutDot);
	$resultWords = [];
	$russianAlphabet = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';

	foreach ($words as $word) {
		$word = trim($word);
		if ($word === '') {
			continue;
		}

		// Проверяем, состоит ли слово только из русских букв
		$isRussian = true;
		for ($i = 0; $i < strlen($word); $i++) {
			if (strpos($russianAlphabet, $word[$i]) === false) {
				$isRussian = false;
				break;
			}
		}

		if (!$isRussian) {
			continue;
		}

		// Если нет двойных согласных то оставляем слово
		if (!hasDoubleConsonants($word)) {
			$resultWords[] = $word;
		}
	}

	$resultString = implode(' ', $resultWords) . '.';

	return [
		'success' => true,
		'result'  => $resultString,
		'count'   => count($resultWords)
	];
}