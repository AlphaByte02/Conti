--
-- PostgreSQL database dump
--

-- Dumped from database version 15.4
-- Dumped by pg_dump version 15.5

-- Started on 2024-08-24 17:39:43 UTC

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

--
-- TOC entry 7 (class 2615 OID 16777)
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 217 (class 1259 OID 16821)
-- Name: nomi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.nomi (
    id bigint NOT NULL,
    nome character varying(127) NOT NULL
);


ALTER TABLE public.nomi OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 16820)
-- Name: nomi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.nomi_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nomi_id_seq OWNER TO postgres;

--
-- TOC entry 3368 (class 0 OID 0)
-- Dependencies: 216
-- Name: nomi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.nomi_id_seq OWNED BY public.nomi.id;


--
-- TOC entry 218 (class 1259 OID 16825)
-- Name: soggetti; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.soggetti (
    id_transazione bigint NOT NULL,
    id_soggetto bigint NOT NULL
);


ALTER TABLE public.soggetti OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 16829)
-- Name: transazioni; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transazioni (
    id bigint NOT NULL,
    id_pagante bigint NOT NULL,
    importo double precision NOT NULL,
    causale character varying(127) DEFAULT NULL::character varying
);


ALTER TABLE public.transazioni OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16828)
-- Name: transazioni_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transazioni_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.transazioni_id_seq OWNER TO postgres;

--
-- TOC entry 3369 (class 0 OID 0)
-- Dependencies: 219
-- Name: transazioni_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transazioni_id_seq OWNED BY public.transazioni.id;


--
-- TOC entry 3207 (class 2604 OID 16824)
-- Name: nomi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nomi ALTER COLUMN id SET DEFAULT nextval('public.nomi_id_seq'::regclass);


--
-- TOC entry 3208 (class 2604 OID 16832)
-- Name: transazioni id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transazioni ALTER COLUMN id SET DEFAULT nextval('public.transazioni_id_seq'::regclass);


--
-- TOC entry 3211 (class 2606 OID 16844)
-- Name: nomi idx_16821_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nomi
    ADD CONSTRAINT idx_16821_primary PRIMARY KEY (id);


--
-- TOC entry 3214 (class 2606 OID 16842)
-- Name: soggetti idx_16825_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.soggetti
    ADD CONSTRAINT idx_16825_primary PRIMARY KEY (id_transazione, id_soggetto);


--
-- TOC entry 3217 (class 2606 OID 16843)
-- Name: transazioni idx_16829_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transazioni
    ADD CONSTRAINT idx_16829_primary PRIMARY KEY (id);


--
-- TOC entry 3212 (class 1259 OID 16835)
-- Name: idx_16825_id_benificiario; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_16825_id_benificiario ON public.soggetti USING btree (id_soggetto);


--
-- TOC entry 3215 (class 1259 OID 16837)
-- Name: idx_16829_pagante; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_16829_pagante ON public.transazioni USING btree (id_pagante);


--
-- TOC entry 3218 (class 2606 OID 17532)
-- Name: soggetti soggetti_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.soggetti
    ADD CONSTRAINT soggetti_ibfk_1 FOREIGN KEY (id_transazione) REFERENCES public.transazioni(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3219 (class 2606 OID 16850)
-- Name: soggetti soggetti_ibfk_2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.soggetti
    ADD CONSTRAINT soggetti_ibfk_2 FOREIGN KEY (id_soggetto) REFERENCES public.nomi(id) ON UPDATE CASCADE;


--
-- TOC entry 3220 (class 2606 OID 16855)
-- Name: transazioni transazioni_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transazioni
    ADD CONSTRAINT transazioni_ibfk_1 FOREIGN KEY (id_pagante) REFERENCES public.nomi(id) ON UPDATE CASCADE;


-- Completed on 2024-08-24 17:39:44 UTC

--
-- PostgreSQL database dump complete
--

