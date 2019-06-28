DROP TYPE IF EXISTS data_source CASCADE;
CREATE TYPE data_source AS ENUM ('OpenWeatherMap', 'Breezometer');

DROP TYPE IF EXISTS measure_category CASCADE;
CREATE TYPE measure_category AS ENUM ('Weather', 'Pollution', 'Pollen');

DROP TABLE IF EXISTS location;
CREATE TABLE location
(
    location_id         SERIAL PRIMARY KEY,
    city                VARCHAR(255) NOT NULL,
    country             CHAR(2) NOT NULL,
    gps                 POINT NOT NULL,
    latitude            DECIMAL(11, 8) NOT NULL,
    longitude           DECIMAL(11, 8) NOT NULL,
    deleted_at          TIMESTAMP(0) WITHOUT TIME ZONE NULL
);

DROP TABLE IF EXISTS measure;
CREATE TABLE measure
(
    measure_id          SERIAL PRIMARY KEY,
    location_id         INT NOT NULL references location(location_id),
    data_source         data_source,
    category            measure_category,
    name                VARCHAR(255) NOT NULL,
    value               FLOAT NOT NULL,
    measured_at         TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    updated_at          TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    created_at          TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
);

DROP TABLE IF EXISTS task;
CREATE TABLE task
(
    task_id             SERIAL PRIMARY KEY,
    command             VARCHAR(255) NOT NULL,
    description         VARCHAR(255) NOT NULL,
    schedule            VARCHAR(64) NULL,
    deleted_at          TIMESTAMP(0) WITHOUT TIME ZONE NULL
);

DROP TABLE IF EXISTS task_history;
CREATE TABLE task_history
(
    task_history_id     SERIAL PRIMARY KEY,
    task_id             INT NOT NULL references task(task_id),
    started_at          TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    ended_at            TIMESTAMP(0) WITHOUT TIME ZONE NULL,
    exit_code           INT NOT NULL default 0,
    comment             TEXT
);

INSERT INTO location (city, country, gps, latitude, longitude) VALUES
    ('Choisy le Roi', 'FR', '(48.755286,2.409039)', 48.755286, 2.409039),
    ('Paris', 'FR', '(48.8534,2.3488)', 48.8534, 2.3488),
    ('Santiago', 'CL', '(-33.4691199,-70.641997)', -33.4691199, -70.641997);

INSERT INTO task (command, description, schedule) VALUES
    ('Cron-OpenWeatherMap', 'Retrieve data from Open Weather Map, every 5 minutes', '*/5 * * * *'),
    ('Cron-Breezometer-Pollen', 'Retrieve data from Breezometer Pollen, every 1 hour', '2 * * * *'),
    ('Cron-Breezometer-Pollution', 'Retrieve data from Breezometer Pollution, every 1 hour', '4 * * * *'),
    ('Cron-Breezometer-Weather', 'Retrieve data from Breezometer Weather, every 1 hour', '6 * * * *');
