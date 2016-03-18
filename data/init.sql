CREATE TABLE cogitoweb_telemetryview_bundle
(
	id SERIAL NOT NULL,
	name VARCHAR(255) NOT NULL,
	sql TEXT DEFAULT NULL,
	active boolean not NULL default true,
	
	createdBy VARCHAR(255) DEFAULT NULL,
	updatedBy VARCHAR(255) DEFAULT NULL,
	createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
	updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,

	PRIMARY KEY(id)
);
CREATE UNIQUE INDEX IDX_cogitoweb_telemetryview_bundle_name ON cogitoweb_telemetryview_bundle (name);