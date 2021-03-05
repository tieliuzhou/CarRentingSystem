-- Generated by Oracle SQL Developer Data Modeler 20.3.0.283.0710
--   at:        2020-12-01 23:12:19 CST
--   site:      Oracle Database 21c
--   type:      Oracle Database 21c



-- predefined type, no DDL - MDSYS.SDO_GEOMETRY

-- predefined type, no DDL - XMLTYPE

CREATE TABLE address (
    addr_id  NUMBER(32) NOT NULL,
    street   VARCHAR2(32) NOT NULL,
    city     VARCHAR2(32) NOT NULL,
    state    VARCHAR2(32) NOT NULL,
    zipcode  NUMBER(5) NOT NULL,
    PRIMARY KEY(addr_id)
);

COMMENT ON COLUMN address.addr_id IS
    'THE UNIQUE ADDRESS ID';

COMMENT ON COLUMN address.street IS
    'THE STREET OF THE  CUSTOMER ADDRESS';

COMMENT ON COLUMN address.city IS
    'THE CITY OF THE  CUSTOMER ADDRESS';

COMMENT ON COLUMN address.state IS
    'THE STATE OF THE  CUSTOMER ADDRESS';

COMMENT ON COLUMN address.zipcode IS
    'THE ZIPCODE OF THE  CUSTOMER ADDRESS';

CREATE TABLE class (
    class_id        NUMBER(32) NOT NULL,
    class_name      VARCHAR2(32) NOT NULL,
    daily_rate      NUMBER(10, 2) NOT NULL,
    over_limit_fee  NUMBER(10, 2) NOT NULL,
    PRIMARY KEY(class_id)
);

COMMENT ON COLUMN class.class_id IS
    'THE UNIQUE ID FOR EACH CLASS';

COMMENT ON COLUMN class.class_name IS
    'THE NAME OF EACH CLASS';

COMMENT ON COLUMN class.daily_rate IS
    'THE RATE OF DAILY RENT';

COMMENT ON COLUMN class.over_limit_fee IS
    'THE OVER MILEAGE FEE';

CREATE TABLE corporate_customer (
    cust_id    NUMBER(32) NOT NULL,
    cust_type  VARCHAR2(1) NOT NULL,
    emp_id     NUMBER(32) NOT NULL,
    corp_id    NUMBER(32) NOT NULL,
    PRIMARY KEY(cust_id, cust_type)
);

COMMENT ON COLUMN corporate_customer.cust_id IS
    'THE UNIQUE ID FOR EACH CUSTOMER OF EACH TYPE';

COMMENT ON COLUMN corporate_customer.cust_type IS
    'THE CUSTOMER TYPE OF EACH CUSTOMER';

COMMENT ON COLUMN corporate_customer.emp_id IS
    'THE EMPLOYEE ID OF EACH EMPLOYEE ';

CREATE TABLE corporation (
    corp_id        NUMBER(32) NOT NULL,
    corp_name      VARCHAR2(32) NOT NULL,
    reg_num        NUMBER(32) NOT NULL,
    corp_discount  NUMBER(3, 3) NOT NULL,
    PRIMARY KEY(corp_id)
);

COMMENT ON COLUMN corporation.corp_id IS
    'THE CORPORATION ID FOR EACH CORPORATION';

COMMENT ON COLUMN corporation.corp_name IS
    'THE NAME OF THE CORPORATION';

COMMENT ON COLUMN corporation.reg_num IS
    'THE REGISTRATION NUMBER OF EACH CORPORATION';

COMMENT ON COLUMN corporation.corp_discount IS
    'THE DISCOUNT OF EACH CORPORATION';

CREATE TABLE coupon (
    coupon_id        NUMBER(32) NOT NULL,
    coupon_discount  NUMBER(3, 3) NOT NULL,
    vld_beg_date     DATE NOT NULL,
    vld_end_date     DATE NOT NULL,
    PRIMARY KEY(coupon_id)    
);

COMMENT ON COLUMN coupon.coupon_id IS
    'THE UNIQUE COUPON ID FOR EACH COUPON';

COMMENT ON COLUMN coupon.coupon_discount IS
    'THE DISCOUNT OF EACH COUPON';

COMMENT ON COLUMN coupon.vld_beg_date IS
    'THE VALID BEGIN DATE OF THE COUPON';

COMMENT ON COLUMN coupon.vld_end_date IS
    'THE VALID END DATE OF THE COUPON';

CREATE TABLE customer (
    cust_id       NUMBER(32) NOT NULL,
    cust_type     VARCHAR2(1) NOT NULL,
    first_name    VARCHAR2(32) NOT NULL,
    last_name     VARCHAR2(32) NOT NULL,
    cust_phone    NUMBER(10) NOT NULL,
    cust_email    VARCHAR2(32) NOT NULL,
    addr_id       NUMBER(32) NOT NULL,
    PRIMARY KEY(cust_id, cust_type)
);

ALTER TABLE customer
    ADD CONSTRAINT ch_inh_customer CHECK ( cust_type IN ( 'C', 'I' ) );

COMMENT ON COLUMN customer.cust_id IS
    'THE UNIQUE ID FOR EACH CUSTOMER OF EACH TYPE';

COMMENT ON COLUMN customer.cust_type IS
    'THE CUSTOMER TYPE OF EACH CUSTOMER';

COMMENT ON COLUMN customer.first_name IS
    'THE FIRST NAME OF AN INDIVIDUAL OR A CORPRATE CUSTOMER';

COMMENT ON COLUMN customer.last_name IS
    'THE LAST NAME OF AN INDIVIDUAL OR A CORPRATE CUSTOMER';

COMMENT ON COLUMN customer.cust_phone IS
    'THE PHONE NUMBER OF THE  CUSTOMER';

COMMENT ON COLUMN customer.cust_email IS
    'THE EMAIL ADDRESS OF THE  CUSTOMER';

COMMENT ON COLUMN customer.cust_zipcode IS
    'THE ZIPCODE OF THE  CUSTOMER ADDRESS';

CREATE TABLE individual_customer (
    cust_id    NUMBER(32) NOT NULL,
    cust_type  VARCHAR2(1) NOT NULL,
    dln        NUMBER(32) NOT NULL,
    icn        VARCHAR2(32) NOT NULL,
    ipn        NUMBER(32) NOT NULL,
    coupon_id  NUMBER(32) NOT NULL,
    PRIMARY KEY(cust_id, cust_type)
);

COMMENT ON COLUMN individual_customer.cust_id IS
    'THE UNIQUE ID FOR EACH CUSTOMER OF EACH TYPE';

COMMENT ON COLUMN individual_customer.cust_type IS
    'THE CUSTOMER TYPE OF EACH CUSTOMER';

COMMENT ON COLUMN individual_customer.dln IS
    'THE DRIVER LICENSE NUMBER OF EACH INDIVIDUAL CUSTOMER';

COMMENT ON COLUMN individual_customer.icn IS
    'THE INSURANCE COMPANY NAME  OF EACH INDIVIDUAL CUSTOMER';

COMMENT ON COLUMN individual_customer.ipn IS
    'THE INSURANCE POLICY NUMBER OF EACH INDIVIDUAL CUSTOMER';

CREATE TABLE invoice (
    inv_id    NUMBER(32) NOT NULL,
    inv_date  DATE NOT NULL,
    amount    NUMBER(32) NOT NULL,
    PRIMARY KEY(inv_id)
);

COMMENT ON COLUMN invoice.inv_id IS
    'THE UNIQUE ID FOR INVOICE';

COMMENT ON COLUMN invoice.inv_date IS
    'THE DATE OF THE INOVICE';

COMMENT ON COLUMN invoice.amount IS
    'THE AMOUNT OF THE TRANSACTION';

CREATE TABLE office_location (
    loc_id       NUMBER(32) NOT NULL,
    loc_street   VARCHAR2(32) NOT NULL,
    loc_city     VARCHAR2(32) NOT NULL,
    loc_state    VARCHAR2(32) NOT NULL,
    loc_zipcode  NUMBER(5) NOT NULL,
    loc_phone    NUMBER(32) NOT NULL,
    PRIMARY KEY(loc_id)
);

COMMENT ON COLUMN office_location.loc_id IS
    'THE UNIQUE IF FOR OFFICE  LOCATION';

COMMENT ON COLUMN office_location.loc_street IS
    'THE STREET OF THE ADDRESS';

COMMENT ON COLUMN office_location.loc_city IS
    'THE CITY OF THE ADDRESS';

COMMENT ON COLUMN office_location.loc_state IS
    'THE STATE OF THE ADDRESS';

COMMENT ON COLUMN office_location.loc_phone IS
    'THE PHONE OF THE RENT OFFICE';

CREATE TABLE payment (
    pmt_id    NUMBER(32) NOT NULL,
    pmt_date  DATE NOT NULL,
    pmt_type  NVARCHAR2(5) NOT NULL,
    pmt_amt   NUMBER(32) NOT NULL,
    card_num  NUMBER(32) NOT NULL,
    inv_id    NUMBER(32) NOT NULL,
    PRIMARY KEY(pmt_id)
);

COMMENT ON COLUMN payment.pmt_id IS
    'THE UNIQUE ID FOR PAYMENT';

COMMENT ON COLUMN payment.pmt_date IS
    'THE DATE OF THE PAYMENT';

COMMENT ON COLUMN payment.pmt_type IS
    'THE TYPE OF PAYMENT';

COMMENT ON COLUMN payment.pmt_amt IS
    'THE AMOUNT OF THE PAYMENT';

COMMENT ON COLUMN payment.card_num IS
    'THE CARD NUMBER OF THE PAYMENT';

CREATE TABLE service (
    serv_id    NUMBER(32) NOT NULL,
    pu_date    DATE NOT NULL,
    do_date    DATE NOT NULL,
    odo_start  NUMBER(32) NOT NULL,
    odo_end    NUMBER(32) NOT NULL,
    dly_lim    NUMBER(32),
    veh_id     NUMBER(32) NOT NULL,
    cust_id    NUMBER(32) NOT NULL,
    cust_type  VARCHAR2(1) NOT NULL,
    inv_id     NUMBER(32) NOT NULL,
    pu_loc_id  NUMBER(32) NOT NULL,
    do_loc_id  NUMBER(32) NOT NULL,
    PRIMARY KEY(serv_id)
);

COMMENT ON COLUMN service.serv_id IS
    'THE SERVICE ID FOR EACH SERVICE';

COMMENT ON COLUMN service.pu_date IS
    'THE PICK UP DATE';

COMMENT ON COLUMN service.do_date IS
    'THE DROP OFF DATE';

COMMENT ON COLUMN service.odo_start IS
    'THE START ODOMETER';

COMMENT ON COLUMN service.odo_end IS
    'THE END ODOMETER ';

COMMENT ON COLUMN service.dly_lim IS
    'THE DAILY ODOMETER LIMIT FOR THE RENTAL SERVICE';

CREATE UNIQUE INDEX service__idx ON
    service (
        inv_id
    ASC );


CREATE TABLE vehicle (
    veh_id    NUMBER(32) NOT NULL,
    make      VARCHAR2(32) NOT NULL,
    model     VARCHAR2(32) NOT NULL,
    year      INTEGER NOT NULL,
    vin       VARCHAR2(32) NOT NULL,
    lpn       VARCHAR2(32) NOT NULL,
    loc_id    NUMBER(32) NOT NULL,
    class_id  NUMBER(32) NOT NULL,
    PRIMARY KEY(veh_id)
);

COMMENT ON COLUMN vehicle.veh_id IS
    'THE UNIQUE ID NUMBER FOR EACH VEHICLE IN THE DATABASE';

COMMENT ON COLUMN vehicle.make IS
    'THE DESCRIPTION ABOUT HOW THE VEHICLE WAS MADE';

COMMENT ON COLUMN vehicle.model IS
    'THE MODEL OF THE VEHICLE';

COMMENT ON COLUMN vehicle.year IS
    'YEAR THAT THE VEHICLE WAS MADE';

COMMENT ON COLUMN vehicle.vin IS
    'VEHICLE IDENTIFICATION NUMBER';

COMMENT ON COLUMN vehicle.lpn IS
    'THE LICENSE PLATE NUMBER';

ALTER TABLE customer
    ADD CONSTRAINT fk_addr_cust FOREIGN KEY ( addr_id )
        REFERENCES address ( addr_id );

ALTER TABLE vehicle
    ADD CONSTRAINT fk_class_veh FOREIGN KEY ( class_id )
        REFERENCES class ( class_id );

ALTER TABLE corporate_customer
    ADD CONSTRAINT fk_corp_corp FOREIGN KEY ( corp_id )
        REFERENCES corporation ( corp_id );

ALTER TABLE individual_customer
    ADD CONSTRAINT fk_coup_ind FOREIGN KEY ( coupon_id )
        REFERENCES coupon ( coupon_id );

ALTER TABLE corporate_customer
    ADD CONSTRAINT fk_cust_corp FOREIGN KEY ( cust_id,
                                              cust_type )
        REFERENCES customer ( cust_id,
                              cust_type );

ALTER TABLE individual_customer
    ADD CONSTRAINT fk_cust_ind FOREIGN KEY ( cust_id,
                                             cust_type )
        REFERENCES customer ( cust_id,
                              cust_type );

ALTER TABLE service
    ADD CONSTRAINT fk_cust_serv FOREIGN KEY ( cust_id,
                                              cust_type )
        REFERENCES customer ( cust_id,
                              cust_type );

ALTER TABLE payment
    ADD CONSTRAINT fk_inv_pmt FOREIGN KEY ( inv_id )
        REFERENCES invoice ( inv_id );

ALTER TABLE service
    ADD CONSTRAINT fk_loc_serv_do FOREIGN KEY ( do_loc_id )
        REFERENCES office_location ( loc_id );

ALTER TABLE service
    ADD CONSTRAINT fk_loc_serv_pu FOREIGN KEY ( pu_loc_id )
        REFERENCES office_location ( loc_id );

ALTER TABLE vehicle
    ADD CONSTRAINT fk_loc_veh FOREIGN KEY ( loc_id )
        REFERENCES office_location ( loc_id );

ALTER TABLE service
    ADD CONSTRAINT fk_serv_inv FOREIGN KEY ( inv_id )
        REFERENCES invoice ( inv_id );

ALTER TABLE service
    ADD CONSTRAINT fk_veh_serv FOREIGN KEY ( veh_id )
        REFERENCES vehicle ( veh_id );

CREATE OR REPLACE TRIGGER arc_fkarc__individual_customer BEFORE
    INSERT OR UPDATE OF cust_id, cust_type ON individual_customer
    FOR EACH ROW
DECLARE
    d VARCHAR2(1);
BEGIN
    SELECT
        a.cust_type
    INTO d
    FROM
        customer a
    WHERE
            a.cust_id = :new.cust_id
        AND a.cust_type = :new.cust_type;

    IF ( d IS NULL OR d <> 'I' ) THEN
        raise_application_error(-20223,
                               'FK FK_CUST_IND in Table INDIVIDUAL_CUSTOMER violates Arc constraint on Table CUSTOMER - discriminator column CUST_TYPE doesn''t have value ''I''');
    END IF;

EXCEPTION
    WHEN no_data_found THEN
        NULL;
    WHEN OTHERS THEN
        RAISE;
END;
/

CREATE OR REPLACE TRIGGER arc_fkarc_1_corporate_customer BEFORE
    INSERT OR UPDATE OF cust_id, cust_type ON corporate_customer
    FOR EACH ROW
DECLARE
    d VARCHAR2(1);
BEGIN
    SELECT
        a.cust_type
    INTO d
    FROM
        customer a
    WHERE
            a.cust_id = :new.cust_id
        AND a.cust_type = :new.cust_type;

    IF ( d IS NULL OR d <> 'C' ) THEN
        raise_application_error(-20223,
                               'FK FK_CUST_CORP in Table CORPORATE_CUSTOMER violates Arc constraint on Table CUSTOMER - discriminator column CUST_TYPE doesn''t have value ''C''');
    END IF;

EXCEPTION
    WHEN no_data_found THEN
        NULL;
    WHEN OTHERS THEN
        RAISE;
END;
/



-- Oracle SQL Developer Data Modeler Summary Report: 
-- 
-- CREATE TABLE                            12
-- CREATE INDEX                             1
-- ALTER TABLE                             26
-- CREATE VIEW                              0
-- ALTER VIEW                               0
-- CREATE PACKAGE                           0
-- CREATE PACKAGE BODY                      0
-- CREATE PROCEDURE                         0
-- CREATE FUNCTION                          0
-- CREATE TRIGGER                           2
-- ALTER TRIGGER                            0
-- CREATE COLLECTION TYPE                   0
-- CREATE STRUCTURED TYPE                   0
-- CREATE STRUCTURED TYPE BODY              0
-- CREATE CLUSTER                           0
-- CREATE CONTEXT                           0
-- CREATE DATABASE                          0
-- CREATE DIMENSION                         0
-- CREATE DIRECTORY                         0
-- CREATE DISK GROUP                        0
-- CREATE ROLE                              0
-- CREATE ROLLBACK SEGMENT                  0
-- CREATE SEQUENCE                          0
-- CREATE MATERIALIZED VIEW                 0
-- CREATE MATERIALIZED VIEW LOG             0
-- CREATE SYNONYM                           0
-- CREATE TABLESPACE                        0
-- CREATE USER                              0
-- 
-- DROP TABLESPACE                          0
-- DROP DATABASE                            0
-- 
-- REDACTION POLICY                         0
-- 
-- ORDS DROP SCHEMA                         0
-- ORDS ENABLE SCHEMA                       0
-- ORDS ENABLE OBJECT                       0
-- 
-- ERRORS                                   0
-- WARNINGS                                 0
