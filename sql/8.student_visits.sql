CREATE TABLE student_visits(
  id             serial,
  student_id     integer,
  video_1       TIMESTAMP,
  video_2       TIMESTAMP,
  video_3       TIMESTAMP,
  video_4       TIMESTAMP,
  video_5       TIMESTAMP,
  video_6       TIMESTAMP,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE student_visits ADD CONSTRAINT student_visits_pk PRIMARY KEY (id);
ALTER TABLE student_visits ADD CONSTRAINT student_visits_uk UNIQUE (student_id);
ALTER TABLE student_visits ADD CONSTRAINT student_visits_fk FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE CASCADE ON DELETE CASCADE;
