<?php

function findThirdSpace($str) { // Найти позиции третьего пробела с конца
    $len = mb_strlen($str, "UTF-8");
    $c = 0; // счётчик пробелов
    $pos = $len;
    while ($c < 3 && $pos) { // Обрезаем три раза строку по конечным пробелам
        $pos = mb_strrpos(mb_substr($str, 0, $pos, "UTF-8"), " ", -1, "UTF-8"); 
        $c++;
        if (!$pos) $pos = 0; // Проверка на отсутствие пробелов
    }
    return $pos;
}

function getPreviewText($text, $link) {
    $publication = trim($text); // Убираем начальные и конечные пробельные символы
    $publication = preg_replace('|\s+|', ' ', $publication);
    // Нужно расширение mbstring для чтения Кириллицы
    $preview = mb_substr($publication,0,250,'UTF-8'); // Берём первые 250 символов
    // С проверкой на всякие неординарные случаи
    if (mb_strlen($preview, "UTF-8") < 250) $preview = $preview . "\u{2026}";
    else if (mb_strrpos($preview, ' ', -1, 'UTF-8') == false) $preview = mb_substr($preview, 0, 249, 'UTF-8') . "\u{2026}";
    else $preview = mb_substr($preview, 0, mb_strrpos($preview, ' ', -1, 'UTF-8'), 'UTF-8') . "\u{2026}"; // Ставим вместо последнего пробела троеточье
    $pos = findThirdSpace($preview); // После этого символа пойдёт ссылка
    $href = basename($link);
    if ($pos == 0) $preview = "<a href=\"publications/$href\">" . mb_substr($preview,$pos, null, "UTF-8") . "</a>";
    else $preview = mb_substr($preview,0,$pos + 1,"UTF-8") . "<a href=\"publications/$href\">" . mb_substr($preview,$pos + 1, null, "UTF-8") . "</a>";
    return $preview;
}

$dir = dirname(__FILE__) . '/publications';
$publications = glob($dir . '/*.html'); // Получили файлы со статьями
foreach ($publications as $file) { // Цикл по файлам статей
    if (file_exists($file)) {
        $text = file_get_contents($file);
        if (preg_match('/<body>(.*)<\/body>/s', $text, $matches)) { // Получили текст статьи
            $preview = getPreviewText($matches[1], $file);
            echo $preview;
            echo "<hr>\n";
        }
    }
}