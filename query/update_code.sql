update fabric set code=(select code from fabric as f2 where fabric.id=f2.id)


ALTER TABLE public.purchaseorder_item ADD COLUMN line character varying;
ALTER TABLE public.shipment ADD COLUMN loadibility character varying;
ALTER TABLE public.products ADD COLUMN client_id integer;


ALTER TABLE public.purchaseorder ADD COLUMN po_client_no character varying;
ALTER TABLE public.purchaseorder ADD COLUMN ship_to text;
ALTER TABLE public.shipment ADD COLUMN tally_user character varying;




INSERT INTO public.menugroup (id, label, icon, apps_type)
VALUES (12, 'Raw Material Inspection', 'icon-tracking', 'EIRO');

INSERT INTO public.menu (id, controller, label, menugroupid, icon, actions, apps_type)
VALUES(821, 'rw_variable_test', 'Variabel Inspection', 12, 'icon_inspection', 'Add|Edit|Delete', 'EIRO');

INSERT INTO public.menu (id, controller, label, menugroupid, icon, actions, apps_type)
VALUES(822, 'inspection_list', 'Inspection List', 12, 'icon_inspection', 'Add|Edit|Delete', 'EIRO');

INSERT INTO public.menu (id, controller, label, menugroupid, icon, actions, apps_type)
VALUES(822, 'inspection_summary', 'Inspection Summary', 12, 'icon_inspection', 'Add|Edit|Delete', 'EIRO');



CREATE SEQUENCE rw_variable_test_id_seq;
REATE SEQUENCE _id_seq;
-- Table: public.rw_variable_test

-- DROP TABLE public.rw_variable_test;

CREATE TABLE public.rw_variable_test
(
  id integer NOT NULL DEFAULT nextval('rw_variable_test_id_seq'::regclass),
  view_position character varying(300) NOT NULL,
  description character varying(250),
  mandatory boolean,
  CONSTRAINT "rw_variable_test: ID must be unique" PRIMARY KEY (id),
  CONSTRAINT rw_variable_test_code_key UNIQUE (view_position)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.rw_variable_test
  OWNER TO postgres;

-- Index: public.rw_variable_test_id_idx

-- DROP INDEX public.rw_variable_test_id_idx;

CREATE INDEX rw_variable_test_id_idx
  ON public.rw_variable_test
  USING btree
  (id);



CREATE SEQUENCE rw_inspection_list_id_seq;
CREATE SEQUENCE rw_inspection_list_detail_id_seq;

CREATE TABLE public.rw_inspection_list
(
    id integer NOT NULL DEFAULT nextval('rw_inspection_list_id_seq'::regclass),
    rw_inspection_list_date date,
    purchaseorder_item_id integer,
    ebako_code character varying,
    customer_code character varying,
    client_id integer,
    client_name character varying,
    submited boolean DEFAULT false,
    user_added character varying,
    added_time timestamp without time zone,
    user_updated character varying,
    updated_time timestamp without time zone,
    po_client_no character varying,
    CONSTRAINT rw_inspection_list_pkey PRIMARY KEY (id),
    CONSTRAINT rw_inspection_list_poitem_fkey FOREIGN KEY (purchaseorder_item_id)
        REFERENCES public.purchaseorder_item (id) MATCH SIMPLE
        ON UPDATE CASCADE ON DELETE CASCADE
);

ALTER TABLE public.rw_inspection_list OWNER TO postgres;

CREATE INDEX rw_inspection_list_id_idx ON public.rw_inspection_list USING btree (id);

CREATE TABLE public.rw_inspection_list_detail
(
    id integer NOT NULL DEFAULT nextval('rw_inspection_list_detail_id_seq'::regclass),
    inspection_id integer,  -- Diperbaiki: inspection_id
    image_category_id integer,
    filename character varying,
    user_added character varying,
    added_time timestamp without time zone,
    user_updated character varying,
    updated_time timestamp without time zone,
    CONSTRAINT rw_inspection_list_detail_pkey PRIMARY KEY (id),
    CONSTRAINT inspection_fkey FOREIGN KEY (inspection_id)  -- Diperbaiki: rw_inspection_list
        REFERENCES public.rw_inspection_list (id) MATCH SIMPLE
        ON UPDATE CASCADE ON DELETE CASCADE
);

ALTER TABLE public.rw_inspection_list_detail OWNER TO postgres;

CREATE INDEX rw_inspection_list_detail_id_idx ON public.rw_inspection_list_detail USING btree (id);

CREATE OR REPLACE FUNCTION public.trg_insert_rw_inspection_list()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO public.rw_inspection_list_detail (inspection_id, image_category_id, filename, user_added, added_time, user_updated, updated_time)
        VALUES (NEW.id, 1, 'default_filename.jpg', NEW.user_added, NEW.added_time, NEW.user_updated, NEW.updated_time); -- Sesuaikan nilai
    ELSIF (TG_OP = 'DELETE') THEN
        DELETE FROM public.rw_inspection_list_detail WHERE inspection_id = OLD.id;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_insert_new_rw_inspection_list
    AFTER INSERT OR DELETE
    ON public.rw_inspection_list
    FOR EACH ROW
    EXECUTE PROCEDURE public.trg_insert_rw_inspection_list();