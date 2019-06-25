create table location
(
    location_id int unsigned auto_increment
        primary key,
    city        varchar(255)   null,
    country     varchar(2)     null,
    latitude    decimal(10, 8) not null,
    longitude   decimal(11, 8) not null,
    updated_at  datetime       not null,
    created_at  datetime       not null,
    deleted_at  datetime       null
);

create table measure
(
    measure_id  bigint unsigned auto_increment
        primary key,
    location_id int unsigned not null,
    name        varchar(255) not null,
    value       float        not null,
    measured_at_utc    datetime     not null,
    updated_at  datetime     not null,
    created_at  datetime     not null,
    constraint measure_location_location_id_fk
        foreign key (location_id) references location (location_id)
            on update cascade
);


INSERT INTO location (city, country, latitude, longitude, updated_at, created_at) VALUES
    ('Choisy le Roi', 'FR', 48.755286, 2.409039, NOW(), NOW()),
    ('Paris', 'FR', 48.8534, 2.3488, NOW(), NOW()),
    ('Santiago', 'CL', -33.4691199, -70.641997, NOW(), NOW());
