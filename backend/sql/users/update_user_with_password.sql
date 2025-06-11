UPDATE users
SET
  name = :NAME,
  email = :EMAIL,
  password = :PASSWORD
WHERE id = :ID;
