SELECT * FROM
(
    SELECT * 
    FROM languages 
    WHERE languages.active = 1
    ORDER BY RAND()
    LIMIT 2
) AS t1
ORDER BY id ASC
