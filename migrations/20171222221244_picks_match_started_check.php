<?php

// Create a check that makes sure we cannot make picks if the game has already
// started

if ($rollback === true) {
    $sql = <<<SQL
DROP TRIGGER picks_match_started_check ON picks;
DROP FUNCTION picks_match_started_check();
SQL;
    $app->db()->query($sql);
    return true;
}

// create users table
$sql = <<<SQL
CREATE FUNCTION picks_match_started_check() RETURNS trigger AS $$
DECLARE
    v_match_time timestamp with time zone;
BEGIN
    v_match_time := (
        SELECT game_time
        FROM matches
        WHERE match_id = NEW.match_id
        LIMIT 1
    );
    IF NEW.updated_at >= v_match_time THEN
        RAISE EXCEPTION 'cannot make picks after game has started';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER picks_match_started_check
    BEFORE INSERT OR UPDATE ON picks
    FOR EACH ROW EXECUTE PROCEDURE picks_match_started_check();
SQL;

$app->db()->query($sql);
