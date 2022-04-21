DROP TABLE student_engagement;
CREATE TABLE student_engagement(
  id                  serial,
  student_id          integer,
  student_id_instance integer,
  page_views          integer,
  participations      integer,
  submissions         integer,

  -- Laravel ORM dates
  created_at TIMESTAMP
  --updated_at TIMESTAMP
);

ALTER TABLE students ADD CONSTRAINT students_unique_instance_uk UNIQUE (id_instance);

ALTER TABLE student_engagement ADD CONSTRAINT student_engagement_pk PRIMARY KEY (id);
ALTER TABLE student_engagement ADD CONSTRAINT student_engagement_fk FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE student_engagement ADD CONSTRAINT student_engagement_instance_fk FOREIGN KEY (student_id_instance) REFERENCES students (id_instance) ON UPDATE CASCADE ON DELETE CASCADE;
