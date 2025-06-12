--
-- PostgreSQL database dump
--

-- Dumped from database version 14.17 (Ubuntu 14.17-1.pgdg22.04+1)
-- Dumped by pg_dump version 14.17 (Ubuntu 14.17-1.pgdg22.04+1)

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: login_histories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.login_histories (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    logged_in_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    ip_address character varying(255),
    role character varying(255)
);


ALTER TABLE public.login_histories OWNER TO postgres;

--
-- Name: login_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.login_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.login_histories_id_seq OWNER TO postgres;

--
-- Name: login_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.login_histories_id_seq OWNED BY public.login_histories.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: overtime_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.overtime_requests (
    id bigint NOT NULL,
    employee_name character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.overtime_requests OWNER TO postgres;

--
-- Name: overtime_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.overtime_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.overtime_requests_id_seq OWNER TO postgres;

--
-- Name: overtime_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.overtime_requests_id_seq OWNED BY public.overtime_requests.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    nip character varying(255),
    username character varying(255),
    role character varying(255) DEFAULT 'user'::character varying NOT NULL,
    employee_id bigint
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: login_histories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login_histories ALTER COLUMN id SET DEFAULT nextval('public.login_histories_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: overtime_requests id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.overtime_requests ALTER COLUMN id SET DEFAULT nextval('public.overtime_requests_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: login_histories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.login_histories (id, user_id, logged_in_at, created_at, updated_at, ip_address, role) FROM stdin;
108	45	2025-05-26 01:11:01	2025-05-26 01:11:11	2025-05-26 01:11:11	127.0.0.1	\N
109	45	2025-05-26 01:18:37	2025-05-26 01:18:47	2025-05-26 01:18:47	127.0.0.1	\N
110	45	2025-05-26 01:25:36	2025-05-26 01:25:46	2025-05-26 01:25:46	127.0.0.1	\N
111	45	2025-05-26 03:21:50	2025-05-26 03:22:00	2025-05-26 03:22:00	127.0.0.1	\N
112	45	2025-05-26 03:50:31	2025-05-26 03:50:41	2025-05-26 03:50:41	127.0.0.1	\N
113	45	2025-05-26 03:53:31	2025-05-26 03:53:41	2025-05-26 03:53:41	127.0.0.1	\N
114	45	2025-05-26 03:54:01	2025-05-26 03:54:11	2025-05-26 03:54:11	127.0.0.1	\N
115	45	2025-05-26 03:54:22	2025-05-26 03:54:32	2025-05-26 03:54:32	127.0.0.1	\N
116	45	2025-05-26 03:56:26	2025-05-26 03:56:36	2025-05-26 03:56:36	127.0.0.1	\N
117	47	2025-05-26 03:57:13	2025-05-26 03:57:23	2025-05-26 03:57:23	127.0.0.1	\N
118	45	2025-05-26 03:58:00	2025-05-26 03:58:10	2025-05-26 03:58:10	127.0.0.1	\N
119	45	2025-05-26 04:19:51	2025-05-26 04:20:01	2025-05-26 04:20:01	127.0.0.1	\N
120	48	2025-05-26 04:25:09	2025-05-26 04:25:19	2025-05-26 04:25:19	127.0.0.1	\N
121	45	2025-05-26 04:25:52	2025-05-26 04:26:02	2025-05-26 04:26:02	127.0.0.1	\N
122	49	2025-05-26 04:40:53	2025-05-26 04:41:03	2025-05-26 04:41:03	127.0.0.1	\N
123	50	2025-05-26 04:42:36	2025-05-26 04:42:46	2025-05-26 04:42:46	127.0.0.1	\N
124	51	2025-05-26 04:44:26	2025-05-26 04:44:36	2025-05-26 04:44:36	127.0.0.1	\N
125	45	2025-05-26 06:28:52	2025-05-26 06:29:02	2025-05-26 06:29:02	127.0.0.1	\N
126	45	2025-05-26 13:39:35	2025-05-26 13:39:45	2025-05-26 13:39:45	127.0.0.1	\N
127	45	2025-05-26 14:14:04	2025-05-26 14:14:14	2025-05-26 14:14:14	127.0.0.1	\N
128	45	2025-05-27 01:00:27	2025-05-27 01:00:37	2025-05-27 01:00:37	127.0.0.1	\N
129	45	2025-05-27 13:16:59	2025-05-27 13:17:09	2025-05-27 13:17:09	127.0.0.1	\N
130	45	2025-05-28 00:55:12	2025-05-28 00:55:22	2025-05-28 00:55:22	127.0.0.1	\N
131	45	2025-05-28 02:01:43	2025-05-28 02:01:53	2025-05-28 02:01:53	127.0.0.1	\N
132	45	2025-05-28 02:38:37	2025-05-28 02:38:47	2025-05-28 02:38:47	127.0.0.1	\N
133	45	2025-05-28 04:06:25	2025-05-28 04:06:35	2025-05-28 04:06:35	127.0.0.1	\N
134	45	2025-05-28 04:43:01	2025-05-28 04:43:11	2025-05-28 04:43:11	127.0.0.1	\N
135	45	2025-05-28 06:25:15	2025-05-28 06:25:25	2025-05-28 06:25:25	127.0.0.1	\N
136	45	2025-05-28 07:04:21	2025-05-28 07:04:31	2025-05-28 07:04:31	127.0.0.1	\N
137	45	2025-05-28 07:12:25	2025-05-28 07:12:35	2025-05-28 07:12:35	127.0.0.1	\N
138	45	2025-05-28 08:19:11	2025-05-28 08:19:21	2025-05-28 08:19:21	127.0.0.1	\N
139	45	2025-05-28 08:23:34	2025-05-28 08:23:44	2025-05-28 08:23:44	127.0.0.1	\N
140	52	2025-05-28 08:26:17	2025-05-28 08:26:27	2025-05-28 08:26:27	127.0.0.1	\N
141	52	2025-05-28 08:28:37	2025-05-28 08:28:47	2025-05-28 08:28:47	127.0.0.1	\N
142	45	2025-05-28 08:29:48	2025-05-28 08:29:58	2025-05-28 08:29:58	127.0.0.1	\N
143	53	2025-05-28 08:47:15	2025-05-28 08:47:25	2025-05-28 08:47:25	127.0.0.1	\N
144	45	2025-05-28 09:05:19	2025-05-28 09:05:29	2025-05-28 09:05:29	127.0.0.1	\N
145	45	2025-05-28 09:08:46	2025-05-28 09:08:56	2025-05-28 09:08:56	127.0.0.1	\N
146	45	2025-05-28 11:47:33	2025-05-28 11:47:43	2025-05-28 11:47:43	127.0.0.1	\N
147	45	2025-05-28 15:50:36	2025-05-28 15:50:46	2025-05-28 15:50:46	127.0.0.1	\N
148	45	2025-05-28 17:19:10	2025-05-28 17:19:20	2025-05-28 17:19:20	127.0.0.1	\N
149	45	2025-05-29 14:02:40	2025-05-29 14:02:50	2025-05-29 14:02:50	127.0.0.1	\N
150	45	2025-05-29 15:59:07	2025-05-29 15:59:17	2025-05-29 15:59:17	127.0.0.1	\N
151	45	2025-05-29 17:10:29	2025-05-29 17:10:39	2025-05-29 17:10:39	127.0.0.1	\N
152	45	2025-05-30 00:52:50	2025-05-30 00:53:00	2025-05-30 00:53:00	127.0.0.1	\N
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_05_09_022513_create_login_histories_table	1
5	2025_05_09_024806_add_ip_address_to_login_histories_table	1
6	2025_05_10_034921_add_role_to_login_histories_table	1
7	2025_05_20_043832_create_overtime_requests_table	2
8	2025_05_24_173545_add_nip_to_users_table	3
10	2025_05_24_174037_add_nip_to_users_table	4
11	2025_05_25_153528_drop_email_from_users_table	5
13	2025_05_25_155035_add_role_to_users_table	6
14	2025_05_26_010631_add_role_to_users_table	7
15	2025_05_26_011031_add_role_to_users_table	8
16	2025_05_28_081836_add_employee_id_to_users_table	9
\.


--
-- Data for Name: overtime_requests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.overtime_requests (id, employee_name, description, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
D7pagZrL8fY7yVDcU5A6Rjxd8lsqyz6Qqyi8vUCD	\N	127.0.0.1	Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoia2dPS3pGcWZLeFhZQmZ4eXNCUVFZSGI3bkU2bHdDa25DazFjSjNVWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=	1748567064
IN0lEFHsNtBJDlBWDJllQzBQkFbTfLjXWhPPqGAO	45	127.0.0.1	Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36	YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ2l2MTJGZXlOYVN3dkZmaGR2MTl1a1dhSGhtTVZmTEdZWW1pWHRBaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9vdmVydGltZS9yZXF1ZXN0Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDU7czoxMToiZW1wbG95ZWVfaWQiO2k6MTI2MDg2O30=	1748542975
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, password, created_at, updated_at, nip, username, role, employee_id) FROM stdin;
1	admin	$2y$12$5r1R7owdg66xG754bhZ2QeVGpicRc0q9YPyCtcwaLPONqcWNbYp.m	2025-05-14 15:03:23	2025-05-14 15:03:23	\N	\N	user	\N
2	ALI SAJIDIN	$2y$12$Csc9JvZsIq1zUzF6FylKROrblNVb4NHwMe7zxWM8ekkxslUlrr6.i	2025-05-14 15:05:06	2025-05-14 15:05:06	\N	\N	user	\N
3	yuda	$2y$12$ZPzBGyuHcDXJPft3EK69TOJhsqvGnskyayGhwbHE6hUyo38L21hhC	2025-05-20 02:49:45	2025-05-20 02:49:45	\N	\N	user	\N
4	imron	$2y$12$eyfnbyCnUukqLOV3paD.XORwm/PL2pKlg5kYsM6rMTK7fezDYXkiG	2025-05-23 02:45:20	2025-05-23 02:45:20	\N	\N	user	\N
5	yai	$2y$12$K1RC5vfjxu4.m0r07Jlsbur9jNW8DxavRs6mIEAUqZq8Hxe7.JCTa	2025-05-23 02:47:31	2025-05-23 02:47:31	\N	\N	user	\N
6	okedong	$2y$12$18HtMxflMLQ1l3a61W3jW.bfsfqcfKNtyV54hCVNh0xFTaGPhYfd6	2025-05-23 02:52:30	2025-05-23 02:52:30	\N	\N	user	\N
7	ssa	$2y$12$SzyiOybEFeU781yDJUL5gOP5ddzcSsgTw2xATXTqdG5gumbLHExQ6	2025-05-23 02:53:00	2025-05-23 02:53:00	\N	\N	user	\N
8	pendeteksi gempa	$2y$12$Ok3hsSpZEXoo7yZugghStexPa.ZF2vTHFOHz8AX081bEItCprssvy	2025-05-23 02:55:15	2025-05-23 02:55:15	\N	\N	user	\N
9	asasaasa	$2y$12$robRR9Qmq.W1YpXoVOA33u6CrMvrgtEfk4qNd2ljxEshBtebcXnMS	2025-05-23 03:00:02	2025-05-23 03:00:02	\N	\N	user	\N
10	asassasssss	$2y$12$lMj02z5TctwscO0OicxqVew7cIOlmVGc3cMhA090usRkK0ZavrV.q	2025-05-23 03:01:14	2025-05-23 03:01:14	\N	\N	user	\N
11	asdfdsasd	$2y$12$ZDtvcGcFjrrJiX82UO0zle84o5e9rNq0a8RwAzng0mmsGLOdMnpM.	2025-05-23 03:02:32	2025-05-23 03:02:32	\N	\N	user	\N
12	asdfdsasd	$2y$12$dEBH4z5Kd8GfsimxtMiQauoWrTpegVqyw4/qV2vCnMGxCaWj3Tlwa	2025-05-23 03:02:51	2025-05-23 03:02:51	\N	\N	user	\N
13	wdccsac	$2y$12$Xbz47gqA5QI3b0EjZEcQ5e9jBRN6W7/UHH6kU5VmWSVgX2rJh1qt2	2025-05-23 03:07:01	2025-05-23 03:07:01	\N	\N	user	\N
14	asiiiii	$2y$12$Y7.Xvf/z0mToEdrtqVU7w.ngzJ4AJXkR.P2aPafByGg6QNlVugHKG	2025-05-23 03:53:22	2025-05-23 03:53:22	\N	\N	user	\N
15	okokokok	$2y$12$bn30w4KRGDCw.NynuVPF3.ZFh4T9Kf4JPbxkJj8MRBNbxZaLV8D4q	2025-05-23 03:56:15	2025-05-23 03:56:15	\N	\N	user	\N
16	okeokeokeoke	$2y$12$66XzPKb5BeuwPg9JWgtrieTTDMfgS9wMtTO46Bc8a0nNZfCghgGVW	2025-05-23 03:58:33	2025-05-23 03:58:33	\N	\N	user	\N
17	FIKA TRI DIANA	$2y$12$PS7PxhNbLU275dLQtXMvBuls3zQrr57swol9FhApowH81qUUc5xL6	2025-05-24 17:44:41	2025-05-24 17:44:41	\N	\N	user	\N
18	AAN ADIYASTUTI	$2y$12$ci0rZlTNkrzkL/PE.pWbb.zBnLTL.cX38KrQ2j4xw7VX88AhxgtnO	2025-05-24 17:47:36	2025-05-24 17:47:36	\N	\N	user	\N
19	ABDUL AZIS A	$2y$12$NxpUqlQDTnFlk8CN4b69u.7hD13jmg4rdTHwAjsAG/YwZQEcwoSka	2025-05-25 13:45:26	2025-05-25 13:45:26	\N	\N	user	\N
24	AAN ADIYASTUTI	$2y$12$unrrqw/o7lhtmIb9X6W5bOekBnufN6V97JoYvo5WyLuhssHs4IRyK	2025-05-25 15:26:20	2025-05-25 15:26:20	\N	\N	user	\N
32	AAN ADIYASTUTI	$2y$12$nPZNeMHQ5xALyiMWcJ0WietZAcZghVa9uZ9sY/uP5tgtFkN2S.45q	2025-05-25 15:35:56	2025-05-25 15:35:56	\N	\N	user	\N
33	AAN ADIYASTUTI	$2y$12$tnHACy/mj/Fb8R3uqepVUOMv4TD9VQUXJRJEo2t59Y7RuNQFlgh1e	2025-05-25 15:36:24	2025-05-25 15:36:24	\N	\N	user	\N
34	ABDUL AZIZ AL HANIF	$2y$12$tEGnEOQaZWUgSFFVdpaOCu.YknVXZT3k.MYbi8dlnLsSvtBf/iX7O	2025-05-25 15:37:17	2025-05-25 15:37:17	\N	\N	user	\N
35	FIKA TRI DIANA	$2y$12$sAFxZMNC5p7nTnyT/usXoOF3Ic8nLE.5IZ7jnnaXBcfBID1VDn.aa	2025-05-25 15:44:02	2025-05-25 15:44:02	\N	\N	user	\N
36	AAN ADIYASTUTI	$2y$12$MCNTiYbgIAavPqTsD3L1WOT6hBnpN2WIAMYQ/0EFkjnFlI4b23UiG	2025-05-25 15:52:04	2025-05-25 15:52:04	\N	\N	user	\N
37	AGUS KRISTIANTO	$2y$12$vLshzFPexgqaL3FGtOuOveSp4Nxo1k/nAIgYicCD78pAsFBtRbWzG	2025-05-25 15:57:20	2025-05-25 15:57:37	10770213	\N	user	\N
39	ABDUL AZIS A	$2y$12$2AXCzUzPe9kzob7gmMhWjuQ8T6RDL8fBugqg6X9Q27WRZuGslSFpa	2025-05-25 16:01:18	2025-05-25 16:01:18	07470312	\N	user	\N
53	RISMA ROSTIANA	$2y$12$r/OkOQyvAYWS3KCzuIiVNOym99PKrS79j6WIfYK6js84p8xaIjk72	2025-05-28 08:47:25	2025-05-28 08:47:25	\N	33901022	admin	125979
40	AGUS WIBOWO	$2y$12$VLOp2YfbAHbgxp4etfFobuFn8LMp.AV7sKqpHkMazzmHpaoVo7A9e	2025-05-25 16:21:38	2025-05-25 16:22:12	19591018	\N	user	\N
48	ABDUL AZIZ AL HANIF	$2y$12$g54fUOrnTweWRiZMJifV/.1Ie38X6tZBufqJaQoj2qbBUn3RFaPbu	2025-05-26 04:25:19	2025-05-26 04:25:19	\N	36001123	admin	\N
41	ANI NURMAWATI	$2y$12$2uq8B8EHdeiVlvCmzeHaou.T0eN4MZDzrAEH5DNA8mOm1WDXSofiK	2025-05-25 16:24:08	2025-05-25 16:24:32	16931216	\N	user	\N
45	AAN ADIYASTUTI	$2y$12$OpIz9WDAH3e0L0EFojaH.OXvYcNxYA8cfFsxPN0GAY1SAJGyZUWba	2025-05-26 01:11:11	2025-05-30 00:53:00	\N	37310724	admin	126086
42	ABDUL AZIZ AL HANIF	$2y$12$OTZy.mLigeBFatuaC.jIKerf32ObRSUbYr8K6KSLZZunwdz9h9Yfu	2025-05-25 17:53:56	2025-05-25 17:53:56	36001123	\N	user	\N
49	AFI ROSIKA	$2y$12$yg3WrteTcV3pMXY6zPyTBOYsXEfrGHyVD3rCD4x5FQLCHbi3MG4xq	2025-05-26 04:41:03	2025-05-26 04:41:03	\N	19190918	admin	\N
38	AAN ADIYASTUTI	$2y$12$/D.uLbysT9dZXT3RPdrlFeeE/.EI/N22i.gbcPsa9RtMVy6LsMSLK	2025-05-25 15:59:52	2025-05-25 19:35:18	37310724	\N	user	\N
50	AFIF AGUSTINO	$2y$12$EL7qHKGlBooqoTE7sLbR8O5x5DYTntxfgELyULp7A9kZZfsrfssES	2025-05-26 04:42:46	2025-05-26 04:42:46	\N	22800319	admin	\N
51	AFIFAH NUR AZMI	$2y$12$qKxM8xBp4hak/gPp8T3T0OOCGyJv/5KmZfzKslckqJQo8NM/lFrLK	2025-05-26 04:44:36	2025-05-26 04:44:36	\N	22710319	admin	\N
46	ABDUL FAQIH	$2y$12$UAOXM740qZaYpQR.sMrrauyumz5.4.mgC0N9X58MkB5oYK71cGTtG	2025-05-26 03:48:31	2025-05-26 03:48:31	\N	\N	admin	\N
52	ACHMAD SYAIFUL	$2y$12$I6X.p/UK8lLyno0JHhNb/OFRqKmJZlwFVAwEcIREjpunlDk/UGHye	2025-05-28 08:26:27	2025-05-28 08:28:47	\N	04301211	admin	127481
47	ABDUL AZIS A	$2y$12$G8TV1GqQ.AItXSsAeacVNefXTdxcOdCC3FrdCI7U92iOuCa9bGo.S	2025-05-26 03:57:23	2025-05-26 03:57:23	\N	07470312	admin	\N
\.


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: login_histories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.login_histories_id_seq', 152, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 16, true);


--
-- Name: overtime_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.overtime_requests_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 53, true);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: login_histories login_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login_histories
    ADD CONSTRAINT login_histories_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: overtime_requests overtime_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.overtime_requests
    ADD CONSTRAINT overtime_requests_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_nip_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_nip_unique UNIQUE (nip);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: users_employee_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_employee_id_index ON public.users USING btree (employee_id);


--
-- Name: login_histories login_histories_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login_histories
    ADD CONSTRAINT login_histories_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

