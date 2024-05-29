SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

-- Name: traducciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
CREATE SEQUENCE public.traducciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER TABLE public.traducciones_id_seq OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

-- Name: traducciones; Type: TABLE; Schema: public; Owner: postgres
CREATE TABLE public.traducciones (
    id integer DEFAULT nextval('public.traducciones_id_seq'::regclass) NOT NULL,
    idioma_entrada character varying(10),
    idioma_salida character varying(10),
    texto character varying(2500),
    traduccion character varying(3000),
    num_caracteres integer,
    fecha timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    formal boolean,
    formato boolean
);

ALTER TABLE public.traducciones OWNER TO postgres;

-- Name: traducciones traducciones_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
ALTER TABLE ONLY public.traducciones
    ADD CONSTRAINT traducciones_pk PRIMARY KEY (id);

-- PostgreSQL database dump complete
