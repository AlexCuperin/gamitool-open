DROP TABLE student_submissions;
CREATE TABLE student_submissions(
  id             serial,
  student_id     integer,

  module_0       TIMESTAMP,
  attempts_0     integer,
  score_0        real,

  module_1       TIMESTAMP,
  attempts_1     integer,
  score_1        real,

  module_2       TIMESTAMP,
  attempts_2     integer,
  score_2        real,

  module_3       TIMESTAMP,
  attempts_3     integer,
  score_3        real,

  module_4       TIMESTAMP,
  attempts_4     integer,
  score_4        real,

  module_5       TIMESTAMP,
  attempts_5     integer,
  score_5        real,

  module_6       TIMESTAMP,
  attempts_6     integer,
  score_6        real,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE student_submissions ADD CONSTRAINT student_submissions_pk PRIMARY KEY (id);
ALTER TABLE student_submissions ADD CONSTRAINT student_submissions_uk UNIQUE (student_id);
ALTER TABLE student_submissions ADD CONSTRAINT student_submissions_fk FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE CASCADE ON DELETE CASCADE;
