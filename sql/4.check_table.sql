CREATE TABLE student_activities(
  id             serial,
  student_id     integer,
  reward_1       TIMESTAMP,
  reward_2       TIMESTAMP,
  reward_3       TIMESTAMP,
  reward_4       TIMESTAMP,
  reward_5       TIMESTAMP,
  reward_6       TIMESTAMP,
  reward_7       TIMESTAMP,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE student_activities ADD CONSTRAINT student_activities_pk PRIMARY KEY (id);
ALTER TABLE student_activities ADD CONSTRAINT student_activities_uk UNIQUE (student_id);
ALTER TABLE student_activities ADD CONSTRAINT student_activities_fk FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE CASCADE ON DELETE CASCADE;
