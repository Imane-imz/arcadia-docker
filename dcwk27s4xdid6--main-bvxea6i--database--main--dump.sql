--
-- PostgreSQL database dump
--

-- Dumped from database version 15.8 (Debian 15.8-1.pgdg110+1)
-- Dumped by pg_dump version 17.0 (Debian 17.0-1.pgdg110+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.rapport_veterinaire DROP CONSTRAINT IF EXISTS fk_ce729cdea76ed395;
ALTER TABLE IF EXISTS ONLY public.rapport_veterinaire DROP CONSTRAINT IF EXISTS fk_ce729cde8e962c16;
ALTER TABLE IF EXISTS ONLY public.nourriture DROP CONSTRAINT IF EXISTS fk_7447e6138e962c16;
ALTER TABLE IF EXISTS ONLY public.animal DROP CONSTRAINT IF EXISTS fk_6aab231faffe2d26;
ALTER TABLE IF EXISTS ONLY public.animal DROP CONSTRAINT IF EXISTS fk_6aab231f6e59d40d;
ALTER TABLE IF EXISTS ONLY public.utilisateur DROP CONSTRAINT IF EXISTS fk_1d1c63b3d60322ac;
DROP TRIGGER IF EXISTS notify_trigger ON public.messenger_messages;
DROP INDEX IF EXISTS public.uniq_identifier_email;
DROP INDEX IF EXISTS public.idx_ce729cdea76ed395;
DROP INDEX IF EXISTS public.idx_ce729cde8e962c16;
DROP INDEX IF EXISTS public.idx_75ea56e0fb7336f0;
DROP INDEX IF EXISTS public.idx_75ea56e0e3bd61ce;
DROP INDEX IF EXISTS public.idx_75ea56e016ba31db;
DROP INDEX IF EXISTS public.idx_7447e6138e962c16;
DROP INDEX IF EXISTS public.idx_6aab231faffe2d26;
DROP INDEX IF EXISTS public.idx_6aab231f6e59d40d;
DROP INDEX IF EXISTS public.idx_1d1c63b3d60322ac;
ALTER TABLE IF EXISTS ONLY public.utilisateur DROP CONSTRAINT IF EXISTS utilisateur_pkey;
ALTER TABLE IF EXISTS ONLY public.service DROP CONSTRAINT IF EXISTS service_pkey;
ALTER TABLE IF EXISTS ONLY public.role DROP CONSTRAINT IF EXISTS role_pkey;
ALTER TABLE IF EXISTS ONLY public.rapport_veterinaire DROP CONSTRAINT IF EXISTS rapport_veterinaire_pkey;
ALTER TABLE IF EXISTS ONLY public.race DROP CONSTRAINT IF EXISTS race_pkey;
ALTER TABLE IF EXISTS ONLY public.nourriture DROP CONSTRAINT IF EXISTS nourriture_pkey;
ALTER TABLE IF EXISTS ONLY public.messenger_messages DROP CONSTRAINT IF EXISTS messenger_messages_pkey;
ALTER TABLE IF EXISTS ONLY public.message DROP CONSTRAINT IF EXISTS message_pkey;
ALTER TABLE IF EXISTS ONLY public.habitat DROP CONSTRAINT IF EXISTS habitat_pkey;
ALTER TABLE IF EXISTS ONLY public.doctrine_migration_versions DROP CONSTRAINT IF EXISTS doctrine_migration_versions_pkey;
ALTER TABLE IF EXISTS ONLY public.avis DROP CONSTRAINT IF EXISTS avis_pkey;
ALTER TABLE IF EXISTS ONLY public.animal DROP CONSTRAINT IF EXISTS animal_pkey;
ALTER TABLE IF EXISTS public.messenger_messages ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE IF EXISTS public.utilisateur_id_seq;
DROP TABLE IF EXISTS public.utilisateur;
DROP SEQUENCE IF EXISTS public.service_id_seq;
DROP TABLE IF EXISTS public.service;
DROP SEQUENCE IF EXISTS public.role_id_seq;
DROP TABLE IF EXISTS public.role;
DROP SEQUENCE IF EXISTS public.rapport_veterinaire_id_seq;
DROP TABLE IF EXISTS public.rapport_veterinaire;
DROP SEQUENCE IF EXISTS public.race_id_seq;
DROP TABLE IF EXISTS public.race;
DROP SEQUENCE IF EXISTS public.nourriture_id_seq;
DROP TABLE IF EXISTS public.nourriture;
DROP SEQUENCE IF EXISTS public.messenger_messages_id_seq;
DROP TABLE IF EXISTS public.messenger_messages;
DROP SEQUENCE IF EXISTS public.message_id_seq;
DROP TABLE IF EXISTS public.message;
DROP SEQUENCE IF EXISTS public.habitat_id_seq;
DROP TABLE IF EXISTS public.habitat;
DROP TABLE IF EXISTS public.doctrine_migration_versions;
DROP SEQUENCE IF EXISTS public.avis_id_seq;
DROP TABLE IF EXISTS public.avis;
DROP SEQUENCE IF EXISTS public.animal_id_seq;
DROP TABLE IF EXISTS public.animal;
DROP FUNCTION IF EXISTS public.notify_messenger_messages();
--
-- Name: notify_messenger_messages(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.notify_messenger_messages() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
                PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: animal; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.animal (
    id integer NOT NULL,
    race_id integer NOT NULL,
    habitat_id integer NOT NULL,
    prenom character varying(255) NOT NULL,
    etat character varying(255) NOT NULL
);


--
-- Name: animal_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.animal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: avis; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.avis (
    id integer NOT NULL,
    pseudo character varying(255) NOT NULL,
    avis text NOT NULL,
    is_visible boolean NOT NULL
);


--
-- Name: avis_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.avis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


--
-- Name: habitat; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.habitat (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    description text NOT NULL,
    commentaire_habitat text NOT NULL,
    image character varying(255) NOT NULL
);


--
-- Name: habitat_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.habitat_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: message; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.message (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    message text NOT NULL
);


--
-- Name: message_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.message_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.messenger_messages (
    id bigint NOT NULL,
    body text NOT NULL,
    headers text NOT NULL,
    queue_name character varying(190) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    available_at timestamp(0) without time zone NOT NULL,
    delivered_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


--
-- Name: COLUMN messenger_messages.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.messenger_messages.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.available_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.messenger_messages.available_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.delivered_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)';


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: nourriture; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.nourriture (
    id integer NOT NULL,
    animal_id integer NOT NULL,
    nourriture character varying(255) NOT NULL,
    quantite integer NOT NULL,
    date timestamp(0) without time zone NOT NULL
);


--
-- Name: nourriture_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.nourriture_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: race; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.race (
    id integer NOT NULL,
    label character varying(255) NOT NULL
);


--
-- Name: race_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.race_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rapport_veterinaire; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rapport_veterinaire (
    id integer NOT NULL,
    user_id integer NOT NULL,
    animal_id integer NOT NULL,
    date timestamp(0) without time zone NOT NULL,
    detail text NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    etat text NOT NULL,
    nourriture character varying(255) NOT NULL,
    gramage integer NOT NULL
);


--
-- Name: COLUMN rapport_veterinaire.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rapport_veterinaire.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN rapport_veterinaire.updated_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rapport_veterinaire.updated_at IS '(DC2Type:datetime_immutable)';


--
-- Name: rapport_veterinaire_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rapport_veterinaire_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: role; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role (
    id integer NOT NULL,
    label character varying(255) NOT NULL
);


--
-- Name: role_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.service (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    description text NOT NULL,
    image character varying(255) NOT NULL
);


--
-- Name: service_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: utilisateur; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.utilisateur (
    id integer NOT NULL,
    role_id integer NOT NULL,
    email character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL,
    prenom character varying(255) NOT NULL,
    nom character varying(255) NOT NULL
);


--
-- Name: utilisateur_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.utilisateur_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Data for Name: animal; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.animal (id, race_id, habitat_id, prenom, etat) FROM stdin;
\.


--
-- Data for Name: avis; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.avis (id, pseudo, avis, is_visible) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20240927211727	2024-09-27 22:17:36	206
\.


--
-- Data for Name: habitat; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.habitat (id, nom, description, commentaire_habitat, image) FROM stdin;
\.


--
-- Data for Name: message; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.message (id, email, message) FROM stdin;
\.


--
-- Data for Name: messenger_messages; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) FROM stdin;
\.


--
-- Data for Name: nourriture; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.nourriture (id, animal_id, nourriture, quantite, date) FROM stdin;
\.


--
-- Data for Name: race; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.race (id, label) FROM stdin;
\.


--
-- Data for Name: rapport_veterinaire; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.rapport_veterinaire (id, user_id, animal_id, date, detail, created_at, updated_at, etat, nourriture, gramage) FROM stdin;
\.


--
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.role (id, label) FROM stdin;
1	Administrateur
\.


--
-- Data for Name: service; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.service (id, nom, description, image) FROM stdin;
\.


--
-- Data for Name: utilisateur; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.utilisateur (id, role_id, email, roles, password, prenom, nom) FROM stdin;
1	1	josearcadia@gmail.com	["ROLE_ADMIN"]	$2y$13$x8IliRRZOhkWDYdpu0h0LeAcHnVAvOW63tFzhZqKPxxu2kd0W0Rbu	José	Da Silva
\.


--
-- Name: animal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.animal_id_seq', 1, false);


--
-- Name: avis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.avis_id_seq', 1, false);


--
-- Name: habitat_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.habitat_id_seq', 1, false);


--
-- Name: message_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.message_id_seq', 1, false);


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.messenger_messages_id_seq', 1, false);


--
-- Name: nourriture_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.nourriture_id_seq', 1, false);


--
-- Name: race_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.race_id_seq', 1, false);


--
-- Name: rapport_veterinaire_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.rapport_veterinaire_id_seq', 1, false);


--
-- Name: role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.role_id_seq', 1, false);


--
-- Name: service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.service_id_seq', 1, true);


--
-- Name: utilisateur_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.utilisateur_id_seq', 1, false);


--
-- Name: animal animal_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.animal
    ADD CONSTRAINT animal_pkey PRIMARY KEY (id);


--
-- Name: avis avis_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT avis_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: habitat habitat_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.habitat
    ADD CONSTRAINT habitat_pkey PRIMARY KEY (id);


--
-- Name: message message_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.message
    ADD CONSTRAINT message_pkey PRIMARY KEY (id);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: nourriture nourriture_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.nourriture
    ADD CONSTRAINT nourriture_pkey PRIMARY KEY (id);


--
-- Name: race race_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.race
    ADD CONSTRAINT race_pkey PRIMARY KEY (id);


--
-- Name: rapport_veterinaire rapport_veterinaire_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rapport_veterinaire
    ADD CONSTRAINT rapport_veterinaire_pkey PRIMARY KEY (id);


--
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);


--
-- Name: service service_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service
    ADD CONSTRAINT service_pkey PRIMARY KEY (id);


--
-- Name: utilisateur utilisateur_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_pkey PRIMARY KEY (id);


--
-- Name: idx_1d1c63b3d60322ac; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_1d1c63b3d60322ac ON public.utilisateur USING btree (role_id);


--
-- Name: idx_6aab231f6e59d40d; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_6aab231f6e59d40d ON public.animal USING btree (race_id);


--
-- Name: idx_6aab231faffe2d26; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_6aab231faffe2d26 ON public.animal USING btree (habitat_id);


--
-- Name: idx_7447e6138e962c16; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_7447e6138e962c16 ON public.nourriture USING btree (animal_id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: idx_ce729cde8e962c16; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_ce729cde8e962c16 ON public.rapport_veterinaire USING btree (animal_id);


--
-- Name: idx_ce729cdea76ed395; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_ce729cdea76ed395 ON public.rapport_veterinaire USING btree (user_id);


--
-- Name: uniq_identifier_email; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_identifier_email ON public.utilisateur USING btree (email);


--
-- Name: messenger_messages notify_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON public.messenger_messages FOR EACH ROW EXECUTE FUNCTION public.notify_messenger_messages();


--
-- Name: utilisateur fk_1d1c63b3d60322ac; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT fk_1d1c63b3d60322ac FOREIGN KEY (role_id) REFERENCES public.role(id);


--
-- Name: animal fk_6aab231f6e59d40d; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.animal
    ADD CONSTRAINT fk_6aab231f6e59d40d FOREIGN KEY (race_id) REFERENCES public.race(id);


--
-- Name: animal fk_6aab231faffe2d26; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.animal
    ADD CONSTRAINT fk_6aab231faffe2d26 FOREIGN KEY (habitat_id) REFERENCES public.habitat(id);


--
-- Name: nourriture fk_7447e6138e962c16; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.nourriture
    ADD CONSTRAINT fk_7447e6138e962c16 FOREIGN KEY (animal_id) REFERENCES public.animal(id);


--
-- Name: rapport_veterinaire fk_ce729cde8e962c16; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rapport_veterinaire
    ADD CONSTRAINT fk_ce729cde8e962c16 FOREIGN KEY (animal_id) REFERENCES public.animal(id);


--
-- Name: rapport_veterinaire fk_ce729cdea76ed395; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rapport_veterinaire
    ADD CONSTRAINT fk_ce729cdea76ed395 FOREIGN KEY (user_id) REFERENCES public.utilisateur(id);


--
-- PostgreSQL database dump complete
--

