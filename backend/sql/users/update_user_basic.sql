UPDATE users
SET
  name = :NAME,
  email = :EMAIL
WHERE id = :ID;
