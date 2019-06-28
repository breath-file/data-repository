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

DROP TABLE IF EXISTS data_source;
CREATE TABLE data_source
(
    data_source_id      SERIAL PRIMARY KEY,
    code                VARCHAR(30) NOT NULL,
    name                VARCHAR(255) NOT NULL,
    deleted_at          TIMESTAMP(0) WITHOUT TIME ZONE NULL
);

DROP TABLE IF EXISTS measure_category;
CREATE TABLE measure_category
(
    measure_category_id SERIAL PRIMARY KEY,
    code                VARCHAR(30) NOT NULL,
    name                VARCHAR(255) NOT NULL,
    deleted_at          TIMESTAMP(0) WITHOUT TIME ZONE NULL
);

DROP TABLE IF EXISTS measure;
CREATE TABLE measure
(
    measure_id          SERIAL PRIMARY KEY,
    location_id         INT NOT NULL references location(location_id),
    data_source_id      INT NOT NULL references data_source(data_source_id),
    measure_category_id INT NOT NULL references measure_category(measure_category_id),
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
    schedule            VARCHAR(64) NOT NULL,
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

INSERT INTO data_source (code, name) VALUES
('openweathermap', 'Open Weather Map'),
('breezometer', 'Breezometer');

INSERT INTO measure_category (code, name) VALUES
('weather', 'Weather'), ('pollution', 'Pollution'), ('pollen', 'Pollens');

INSERT INTO task (command, description, schedule) VALUES
('ImportOpenWeatherMapTask', 'Retrieve data from Open Weather Map, every 5 minutes', '*/5 * * * *'),
('ImportBreezometerTask', 'Retrieve data from Breezometer, every 1 hour', '2 * * * *');
