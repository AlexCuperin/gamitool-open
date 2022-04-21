BEGIN;
UPDATE resources SET module = 6 WHERE module = 5;
UPDATE resources SET module = 5 WHERE module = 4;
UPDATE resources SET module = 4 WHERE module = 3;
UPDATE resources SET module = 3 WHERE module = 2;
UPDATE resources SET module = 2 WHERE module = 1;
UPDATE resources SET module = 1 WHERE module = 0;

UPDATE resources SET row = 6 WHERE row = 5;
UPDATE resources SET row = 5 WHERE row = 4;
UPDATE resources SET row = 4 WHERE row = 3;
UPDATE resources SET row = 3 WHERE row = 2;
UPDATE resources SET row = 2 WHERE row = 1;
UPDATE resources SET row = 1 WHERE row = 0;
END;
