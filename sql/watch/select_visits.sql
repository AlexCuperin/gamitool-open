SELECT s.id_instance, s.email_instance, v.* FROM student_visits v JOIN students s ON student_id = s.id ORDER BY id DESC LIMIT 25;
