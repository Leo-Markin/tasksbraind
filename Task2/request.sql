SELECT rating AS response # Запрашиваем оценку
FROM Ratings # Из таблицы ratings
WHERE PublicationID IN ( # Где ID публикации
    SELECT PublicationID
    FROM Authorship #В таблице авторства
    WHERE AuthorID = 4 # Равен ID выбранного автора
)
UNION ALL # Объединяем сохраняя дубликаты
SELECT text AS response # Запрашиваем текст комментария
FROM Comments # Из таблицы комментариев
WHERE PublicationID IN (
    SELECT PublicationID
    FROM Authorship
    WHERE AuthorID = 4 # Равен ID выбранного автора 
);