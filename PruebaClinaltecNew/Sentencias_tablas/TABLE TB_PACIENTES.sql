CREATE TABLE TB_PACIENTES (
    ID							INT NOT NULL IDENTITY(1,1),
    NUMERO_DOCUMENTO            BIGINT,
    NOMBRE                      TEXT,
    EDAD                        INT,
    GENERO_ID                   INT,
    DEPARTAMENTO_ID             INT,
    MUNICIPIO_ID                INT,
    FECHA_CREATE                DATE
	CONSTRAINT PK_PACIENTES UNIQUE (ID)
);