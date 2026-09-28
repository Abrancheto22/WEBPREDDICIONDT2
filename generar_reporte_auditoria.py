#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Generador de Informe Ejecutivo y Técnico de Auditoría:
Flujo del Sistema (Frontend y Backend) y Evaluación de Brechas de Seguridad (OWASP Top 10)
para el Sistema WebPrediccionDT2.
"""

import os
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import cm
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle,
    HRFlowable, PageBreak, KeepTogether
)
from reportlab.lib.enums import TA_LEFT, TA_CENTER, TA_RIGHT, TA_JUSTIFY
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super().showPage()
        super().save()

    def draw_page_decorations(self, total_pages):
        self.saveState()
        self.setFont("Helvetica", 7.5)
        self.setFillColor(colors.HexColor("#546e7a"))

        # Header (páginas > 1)
        if self._pageNumber > 1:
            self.drawString(1.8 * cm, 28.5 * cm, "AUDITORÍA TÉCNICA Y SEGURIDAD | SISTEMA WEBPREDDICIONDT2")
            self.drawRightString(19.2 * cm, 28.5 * cm, "ESTADO FUNCIONAL & VULNERABILIDADES")
            self.setStrokeColor(colors.HexColor("#cfd8dc"))
            self.setLineWidth(0.5)
            self.line(1.8 * cm, 28.3 * cm, 19.2 * cm, 28.3 * cm)

        # Footer (todas las páginas)
        self.setStrokeColor(colors.HexColor("#cfd8dc"))
        self.setLineWidth(0.5)
        self.line(1.8 * cm, 1.6 * cm, 19.2 * cm, 1.6 * cm)
        self.drawString(1.8 * cm, 1.1 * cm, "CONFIDENCIAL — AUDITORÍA CLÍNICA DE SEGURIDAD INFORMÁTICA")
        self.drawRightString(19.2 * cm, 1.1 * cm, f"Página {self._pageNumber} de {total_pages}")
        self.restoreState()

def build_audit_pdf(filename="Reporte_Auditoria_Flujo_y_Seguridad_WebPrediccionDT2.pdf"):
    doc = SimpleDocTemplate(
        filename,
        pagesize=A4,
        leftMargin=1.8 * cm,
        rightMargin=1.8 * cm,
        topMargin=2.0 * cm,
        bottomMargin=2.0 * cm
    )

    styles = getSampleStyleSheet()
    
    # Custom styles
    title_style = ParagraphStyle(
        'DocTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=15,
        leading=19,
        textColor=colors.HexColor('#0d233a'),
        alignment=TA_CENTER
    )
    
    subtitle_style = ParagraphStyle(
        'DocSubtitle',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=9.5,
        leading=13,
        textColor=colors.HexColor('#37474f'),
        alignment=TA_CENTER
    )

    h1_style = ParagraphStyle(
        'Header1',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=11.5,
        leading=15,
        textColor=colors.white,
        alignment=TA_LEFT
    )

    h2_style = ParagraphStyle(
        'Header2',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=10,
        leading=13,
        textColor=colors.HexColor('#102a43'),
        spaceBefore=6,
        spaceAfter=3
    )

    body_style = ParagraphStyle(
        'BodyDark',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=12,
        textColor=colors.HexColor('#243b53'),
        alignment=TA_JUSTIFY,
        spaceAfter=4
    )

    bullet_style = ParagraphStyle(
        'BulletText',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.2,
        leading=11.5,
        textColor=colors.HexColor('#243b53'),
        alignment=TA_JUSTIFY,
        leftIndent=12,
        spaceAfter=2
    )

    table_header = ParagraphStyle(
        'TH',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=10,
        textColor=colors.white,
        alignment=TA_CENTER
    )

    table_cell = ParagraphStyle(
        'TC',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=7.5,
        leading=10,
        textColor=colors.HexColor('#102a43')
    )

    table_cell_bold = ParagraphStyle(
        'TCB',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=7.5,
        leading=10,
        textColor=colors.HexColor('#102a43')
    )

    table_cell_center = ParagraphStyle(
        'TCC',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=7.5,
        leading=10,
        alignment=TA_CENTER,
        textColor=colors.HexColor('#102a43')
    )

    def section_banner(text, bg_color="#102a43"):
        p = Paragraph(f"<b>{text}</b>", h1_style)
        t = Table([[p]], colWidths=[17.4 * cm])
        t.setStyle(TableStyle([
            ('BACKGROUND', (0, 0), (-1, -1), colors.HexColor(bg_color)),
            ('TOPPADDING', (0, 0), (-1, -1), 5),
            ('BOTTOMPADDING', (0, 0), (-1, -1), 5),
            ('LEFTPADDING', (0, 0), (-1, -1), 8),
            ('RIGHTPADDING', (0, 0), (-1, -1), 8),
        ]))
        return t

    def alert_box(title, text, border_color="#d32f2f", bg_color="#ffebee"):
        content = [
            Paragraph(f"<b>{title}</b>", ParagraphStyle('AlertH', fontName='Helvetica-Bold', fontSize=8.5, leading=11, textColor=colors.HexColor(border_color))),
            Spacer(1, 2),
            Paragraph(text, ParagraphStyle('AlertB', fontName='Helvetica', fontSize=8, leading=11.5, textColor=colors.HexColor('#1a1a1a'), alignment=TA_JUSTIFY))
        ]
        t = Table([[content]], colWidths=[17.4 * cm])
        t.setStyle(TableStyle([
            ('BACKGROUND', (0, 0), (-1, -1), colors.HexColor(bg_color)),
            ('BOX', (0, 0), (-1, -1), 1, colors.HexColor(border_color)),
            ('TOPPADDING', (0, 0), (-1, -1), 6),
            ('BOTTOMPADDING', (0, 0), (-1, -1), 6),
            ('LEFTPADDING', (0, 0), (-1, -1), 10),
            ('RIGHTPADDING', (0, 0), (-1, -1), 10),
        ]))
        return t

    story = []

    # ─────────────────────────────────────────────────────────────────────────────
    # PORTADA / ENCABEZADO
    # ─────────────────────────────────────────────────────────────────────────────
    story.append(Paragraph("INFORME DE AUDITORÍA TÉCNICA INTEGRAL", title_style))
    story.append(Paragraph("Evaluación de Flujo Funcional (Frontend & Backend) y Brechas de Seguridad (OWASP Top 10)", subtitle_style))
    story.append(Spacer(1, 3))
    story.append(HRFlowable(width="100%", thickness=1.5, color=colors.HexColor('#0d233a'), spaceBefore=2, spaceAfter=8))

    meta_table_data = [
        [
            Paragraph("<b>Sistema Evaluado:</b> WebPrediccionDT2 (Laravel 11 + Supabase)", table_cell),
            Paragraph("<b>Fecha de Auditoría:</b> 27 de Septiembre, 2026", table_cell),
        ],
        [
            Paragraph("<b>Microservicio ML:</b> AppML-Tesis (Python Flask + Random Forest)", table_cell),
            Paragraph("<b>Entorno de Ejecución:</b> Vercel Serverless / AWS Pooler", table_cell),
        ],
        [
            Paragraph("<b>Normativas de Referencia:</b> OWASP Top 10:2021 | HIPAA / Ley 29733", table_cell),
            Paragraph("<b>Dictamen Global:</b> <font color='#c62828'><b>RIESGO ALTO (Requiere Parches RBAC & Config)</b></font>", table_cell),
        ]
    ]
    t_meta = Table(meta_table_data, colWidths=[9.0 * cm, 8.4 * cm])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, -1), colors.HexColor('#f0f4f8')),
        ('BOX', (0, 0), (-1, -1), 0.5, colors.HexColor('#bcccdc')),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor('#d9e2ec')),
        ('TOPPADDING', (0, 0), (-1, -1), 4),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 10))

    # ─────────────────────────────────────────────────────────────────────────────
    # CAPÍTULO 1: ARQUITECTURA Y FLUJO FUNCIONAL
    # ─────────────────────────────────────────────────────────────────────────────
    story.append(section_banner("1. ARQUITECTURA Y FLUJO FUNCIONAL END-TO-END"))
    story.append(Spacer(1, 6))

    story.append(Paragraph(
        "El sistema <b>WebPrediccionDT2</b> implementa un ecosistema médico distribuido en tres capas principales: "
        "interfaz de usuario Blade/JavaScript (Frontend), capa de negocio y control Laravel 11 (Backend), y motor de inferencia analítica dual "
        "(Microservicio Python Random Forest y Google Gemini 1.5 Flash para asistencia diagnóstica). A continuación se audita el flujo funcional ciclo por ciclo:",
        body_style
    ))

    flow_data = [
        ["Fase / Módulo", "Tecnología & Ruta", "Descripción del Flujo", "Estado Funcional"],
        [
            Paragraph("<b>1. Acceso & Sesión</b>", table_cell_bold),
            Paragraph("<font face='Courier'>/login</font><br/><font face='Courier'>/register</font>", table_cell),
            Paragraph("Autenticación por correo y contraseña con Hash bcrypt. El registro público asigna automáticamente el rol Paciente (idrol=4) y genera un registro temporal en la tabla paciente.", table_cell),
            Paragraph("<font color='#2e7d32'><b>OPERATIVO</b></font><br/>(Falta Rate Limit)", table_cell_center)
        ],
        [
            Paragraph("<b>2. Citas & Triaje</b>", table_cell_bold),
            Paragraph("<font face='Courier'>/citas</font><br/><font face='Courier'>/triajes/create</font>", table_cell),
            Paragraph("Recepción médica y enfermería. Se registran signos vitales (glucosa, presión, IMC, edad, etc.). La cita pasa a estado 'Triado' para ser atendida por el médico.", table_cell),
            Paragraph("<font color='#2e7d32'><b>OPERATIVO</b></font>", table_cell_center)
        ],
        [
            Paragraph("<b>3. Inferencia ML (Random Forest)</b>", table_cell_bold),
            Paragraph("<font face='Courier'>/predicciones/create</font><br/>$\rightarrow$ <font face='Courier'>appml-tesis.vercel.app</font>", table_cell),
            Paragraph("El médico consulta los datos precargados del triaje. Al pulsar 'Predecir Diabetes', Laravel efectúa una llamada HTTP POST al microservicio Flask con los 8 parámetros normalizados. Devuelve probabilidad soft y diagnóstico preliminar.", table_cell),
            Paragraph("<font color='#2e7d32'><b>OPERATIVO</b></font>", table_cell_center)
        ],
        [
            Paragraph("<b>4. Asistencia IA Generativa</b>", table_cell_bold),
            Paragraph("<font face='Courier'>/predicciones/analizar-gemini</font>", table_cell),
            Paragraph("Analiza antecedentes clínicos y adjuntos (PDF/DOCX/JPG). Si GEMINI_API_KEY no está presente en .env, arroja excepción controlada.", table_cell),
            Paragraph("<font color='#e65100'><b>REQUIERE KEY</b></font><br/>(En .env)", table_cell_center)
        ],
        [
            Paragraph("<b>5. Validación Médica (Instrumento 3)</b>", table_cell_bold),
            Paragraph("<font face='Courier'>/predicciones</font><br/><font face='Courier'>{id}/validar</font>", table_cell),
            Paragraph("El especialista valida interactivamente el diagnóstico clínico real (Sí=Diabético / No=Sano) mediante AJAX con token CSRF. Sincronizado con los 80 pacientes evaluados en Casa Grande.", table_cell),
            Paragraph("<font color='#2e7d32'><b>OPERATIVO</b></font><br/>(Accuracy 70.0%)", table_cell_center)
        ],
        [
            Paragraph("<b>6. Auditoría & Matriz Confusión</b>", table_cell_bold),
            Paragraph("<font face='Courier'>/doctores/costos</font><br/><font face='Courier'>/confusion/export</font>", table_cell),
            Paragraph("Cálculo del costo operacional por doctor (COPG) y cálculo global de Matriz de Confusión en tiempo real: TP=19, TN=37, FP=6, FN=18 a umbral óptimo 0.55 con exportación a Excel.", table_cell),
            Paragraph("<font color='#2e7d32'><b>OPERATIVO</b></font>", table_cell_center)
        ]
    ]

    t_flow = Table(flow_data, colWidths=[3.2 * cm, 3.2 * cm, 8.5 * cm, 2.5 * cm])
    t_flow.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#102a43')),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor('#d9e2ec')),
        ('TOPPADDING', (0, 0), (-1, -1), 4),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
        ('VALIGN', (0, 0), (-1, -1), 'TOP'),
        ('ROWBACKGROUNDS', (0, 1), (-1, -1), [colors.white, colors.HexColor('#f8fafc')]),
    ]))
    story.append(t_flow)
    story.append(Spacer(1, 10))

    # ─────────────────────────────────────────────────────────────────────────────
    # CAPÍTULO 2: EVALUACIÓN DE VULNERABILIDADES (OWASP TOP 10)
    # ─────────────────────────────────────────────────────────────────────────────
    story.append(section_banner("2. AUDITORÍA DE SEGURIDAD Y BRECHAS VULNERABLES (OWASP TOP 10)", "#b71c1c"))
    story.append(Spacer(1, 6))

    story.append(Paragraph(
        "Se realizó un escaneo estático y dinámico de arquitectura, código fuente en controladores, rutas, middlewares y configuraciones. "
        "A continuación se detallan las vulnerabilidades críticas, altas y moderadas identificadas:",
        body_style
    ))

    vuln_summary_data = [
        ["ID", "Vulnerabilidad Identificada", "Clasificación OWASP", "Severidad", "Impacto Clínico"],
        ["V-01", "Falta de Middleware RBAC en Rutas Administrativas", "A01:2021 Broken Access Control", "CRÍTICA", "Escalación de privilegios de Paciente a Administrador"],
        ["V-02", "Insecure Direct Object Reference (IDOR) en Pacientes y Predicciones", "A01:2021 Broken Access Control", "CRÍTICA", "Fuga de expedientes clínicos de terceros por ID"],
        ["V-03", "Exfiltración de Reportes Clínicos vía Endpoint SendEmail", "A01:2021 Broken Access Control", "ALTA", "Envío no autorizado de historial médico a correos externos"],
        ["V-04", "Persistencia de Archivos Locales en Vercel Serverless (Read-Only)", "A05:2021 Security Misconfiguration", "ALTA", "Fallo 500 al subir avatares y pérdida de adjuntos en /tmp"],
        ["V-05", "Entorno con Depuración Activa (APP_DEBUG=true)", "A05:2021 Security Misconfiguration", "ALTA", "Fuga de credenciales de Supabase y rutas de servidor"],
        ["V-06", "Microservicio ML sin Autenticación y CORS Abierto (*)", "A07:2021 Auth Failures", "MEDIA", "Consumo no autorizado de cómputo y riesgo de denegación"],
        ["V-07", "Ausencia de Rate Limiting en Autenticación (/login)", "A07:2021 Auth Failures", "MEDIA", "Ataques de fuerza bruta y credential stuffing"],
        ["V-08", "Renderizado HTML de IA sin Sanitización ({!! $analisis_ia !!})", "A03:2021 Injection (Stored XSS)", "BAJA", "Posible inyección XSS si un adjunto malicioso engaña a Gemini"],
    ]

    t_vuln = Table([[Paragraph(c, table_header if i==0 else (table_cell_bold if j==0 or j==3 else table_cell)) 
                     for j, c in enumerate(row)] for i, row in enumerate(vuln_summary_data)],
                   colWidths=[1.1 * cm, 5.0 * cm, 4.3 * cm, 2.0 * cm, 5.0 * cm])
    t_vuln.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#822727')),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor('#ef9a9a')),
        ('TOPPADDING', (0, 0), (-1, -1), 3.5),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 3.5),
        ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0, 1), (-1, -1), [colors.white, colors.HexColor('#fff5f5')]),
    ]))
    story.append(t_vuln)
    story.append(Spacer(1, 10))

    story.append(PageBreak())

    # ─────────────────────────────────────────────────────────────────────────────
    # DETALLE TÉCNICO DE VULNERABILIDADES
    # ─────────────────────────────────────────────────────────────────────────────
    story.append(section_banner("DETALLE DE BRECHAS Y PRUEBAS DE CONCEPTO TÉCNICAS", "#822727"))
    story.append(Spacer(1, 6))

    # V-01
    story.append(alert_box(
        "V-01: Broken Access Control — Falta de Middleware de Roles (SEVERIDAD: CRÍTICA)",
        "<b>Evidencia en Código:</b> En <font face='Courier'>routes/web.php</font>, el grupo completo de rutas administrativas "
        "(<font face='Courier'>/users</font>, <font face='Courier'>/roles</font>, <font face='Courier'>/users/create</font>, <font face='Courier'>/users/{id}/edit</font>) se encuentra protegido únicamente "
        "por el middleware <font face='Courier'>auth</font>:<br/>"
        "<font face='Courier'>Route::middleware(['auth'])->group(function () { Route::get('/users', [IndexController::class, 'users']); ... });</font><br/>"
        "<b>Mecánica de Vulnerabilidad:</b> Cuando un paciente se registra en <font face='Courier'>/register</font>, obtiene el rol 4. Si el paciente navega a "
        "<font face='Courier'>/users</font>, el sistema lista a todos los médicos y administradores. El paciente puede editar a cualquier administrador o "
        "cambiar su propio <font face='Courier'>idrol</font> a 1 (Admin), logrando escalada de privilegios total.<br/>"
        "<b>Remediación:</b> Crear un Middleware <font face='Courier'>RoleMiddleware</font> y aplicarlo como <font face='Courier'>['auth', 'role:admin']</font> sobre las rutas críticas.",
        border_color="#c62828", bg_color="#ffebee"
    ))
    story.append(Spacer(1, 6))

    # V-02
    story.append(alert_box(
        "V-02: Insecure Direct Object References (IDOR) en Expedientes (SEVERIDAD: CRÍTICA)",
        "<b>Evidencia en Código:</b> En <font face='Courier'>PrediccionController@show($idprediccion)</font>, <font face='Courier'>@edit</font> y <font face='Courier'>@downloadAttachment($id, $index)</font>:<br/>"
        "<font face='Courier'>$prediccion = Prediccion::findOrFail($id); return view('predicciones.show', compact('prediccion'));</font><br/>"
        "<b>Mecánica de Vulnerabilidad:</b> No se valida si el <font face='Courier'>Auth::user()->id</font> pertenece al doctor tratante ni al paciente objeto del reporte. "
        "Un usuario autenticado puede solicitar secuencialmente <font face='Courier'>/predicciones/1</font> hasta <font face='Courier'>/predicciones/80</font> o descargar archivos clínicos adjuntos de cualquier persona.<br/>"
        "<b>Remediación:</b> Implementar Políticas de Autorización (<font face='Courier'>Gate::authorize('view', $prediccion)</font>) verificando que el usuario sea el doctor o paciente asociado.",
        border_color="#c62828", bg_color="#ffebee"
    ))
    story.append(Spacer(1, 6))

    # V-03
    story.append(alert_box(
        "V-03: Fuga de Datos Clínicos por Exfiltración en SendEmail (SEVERIDAD: ALTA)",
        "<b>Evidencia en Código:</b> En <font face='Courier'>PrediccionController@sendEmail(Request $request, $idprediccion)</font>:<br/>"
        "<font face='Courier'>$request->validate(['email' => 'required|email']);</font><br/>"
        "<font face='Courier'>Mail::send(..., function($m) use ($request, $pdf) { $m->to($request->email)->attachData(...); });</font><br/>"
        "<b>Mecánica de Vulnerabilidad:</b> El endpoint permite ingresar cualquier correo de destino sin validar destinatario legítimo. "
        "Cualquier usuario puede exfiltrar el informe médico confidencial en PDF de cualquier paciente hacia casillas de correo externas no reguladas.<br/>"
        "<b>Remediación:</b> Enviar reportes únicamente a la dirección de correo registrada en el perfil del paciente o doctor autenticado.",
        border_color="#e65100", bg_color="#fff3e0"
    ))
    story.append(Spacer(1, 6))

    # V-04
    story.append(alert_box(
        "V-04: Conflicto de Almacenamiento Local en Vercel Serverless (SEVERIDAD: ALTA)",
        "<b>Evidencia en Código:</b> En <font face='Courier'>PacienteController@update</font>:<br/>"
        "<font face='Courier'>$path = $file->move(public_path('images/pacientes'), $nombreImagen);</font><br/>"
        "<b>Mecánica del Problema:</b> Las funciones Serverless en Vercel montan un sistema de archivos de SOLO LECTURA. Intentar escribir en <font face='Courier'>public_path()</font> "
        "arrojará <font face='Courier'>ErrorException: Read-only file system</font> (Error 500). Además, los adjuntos en <font face='Courier'>/tmp</font> desaparecen tras reciclar la instancia lambda.<br/>"
        "<b>Remediación:</b> Configurar el controlador de almacenamiento para utilizar <b>Supabase Storage Bucket</b> vía API S3 o REST.",
        border_color="#e65100", bg_color="#fff3e0"
    ))
    story.append(Spacer(1, 6))

    # V-05 y V-06
    v_row = [
        [
            Paragraph("<b>V-05: Entorno con APP_DEBUG=true</b>", table_cell_bold),
            Paragraph("<b>V-06: Microservicio ML con CORS Libre (*)</b>", table_cell_bold)
        ],
        [
            Paragraph("<b>Riesgo:</b> En caso de excepción no capturada en Vercel, Laravel despliega la pantalla Ignition exponiendo contraseñas de base de datos, Session Pooler de Supabase y tokens.<br/><b>Solución:</b> Configurar <font face='Courier'>APP_DEBUG=false</font> y <font face='Courier'>APP_ENV=production</font>.", table_cell),
            Paragraph("<b>Riesgo:</b> <font face='Courier'>app.py</font> tiene <font face='Courier'>CORS(origins='*')</font> sin API Key. Cualquier sitio externo puede ejecutar inferencias abusivas agotando recursos del servidor.<br/><b>Solución:</b> Restringir CORS al dominio web y validar cabecera <font face='Courier'>X-API-Key</font>.", table_cell)
        ]
    ]
    t_vrow = Table(v_row, colWidths=[8.5 * cm, 8.5 * cm])
    t_vrow.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, -1), colors.HexColor('#f8fafc')),
        ('BOX', (0, 0), (-1, -1), 0.5, colors.HexColor('#bcccdc')),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor('#d9e2ec')),
        ('TOPPADDING', (0, 0), (-1, -1), 4),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
    ]))
    story.append(t_vrow)
    story.append(Spacer(1, 10))

    # ─────────────────────────────────────────────────────────────────────────────
    # CAPÍTULO 3: ESTADO DE LA BASE DE DATOS Y RLS
    # ─────────────────────────────────────────────────────────────────────────────
    story.append(section_banner("3. AUDITORÍA DE BASE DE DATOS SUPABASE & RLS"))
    story.append(Spacer(1, 6))

    story.append(Paragraph(
        "Se auditó la configuración de seguridad a nivel de motor PostgreSQL en la instancia Supabase (Session Pooler):",
        body_style
    ))

    db_audit_data = [
        ["Componente de Base de Datos", "Configuración Detectada", "Diagnóstico de Seguridad", "Recomendación Técnica"],
        [
            Paragraph("<b>Row Level Security (RLS)</b>", table_cell_bold),
            Paragraph("Habilitado en las 16 tablas públicas (<font face='Courier'>relrowsecurity=True</font>), con 0 políticas declaradas.", table_cell),
            Paragraph("<font color='#2e7d32'><b>PROTEGIDO POR DEFECTO</b></font><br/>La API REST anónima devuelve <font face='Courier'>[]</font> vacío ante intentos de lectura de pacientes/predicciones.", table_cell),
            Paragraph("Mantener RLS activo. Si se usa cliente JS en frontend, declarar políticas estrictas por <font face='Courier'>auth.uid()</font>.", table_cell)
        ],
        [
            Paragraph("<b>Conexión Laravel Backend</b>", table_cell_bold),
            Paragraph("Usuario <font face='Courier'>postgres.wtmvupwxovhqgvvozwer</font> conectando a través del Pooler puerto 5432.", table_cell),
            Paragraph("<font color='#2e7d32'><b>BYPASS RLS LEGÍTIMO</b></font><br/>El superusuario postgres opera con privilegios de sistema, permitiendo que Laravel controle la lógica.", table_cell),
            Paragraph("Preservar credenciales en <font face='Courier'>.env</font> fuera del repositorio git y forzar <font face='Courier'>sslmode=require</font>.", table_cell)
        ],
        [
            Paragraph("<b>Integridad Instrumento 3</b>", table_cell_bold),
            Paragraph("80 registros actualizados con probabilidades exactas y validación médica pretest.", table_cell),
            Paragraph("<font color='#2e7d32'><b>100% CONSISTENTE</b></font><br/>TP=19, TN=37, FP=6, FN=18, Exactitud=70.0% idéntica a Tabla 11 de la tesis.", table_cell),
            Paragraph("Datos validados en Supabase y en la vista <font face='Courier'>/doctores/costos</font> con umbral 0.55.", table_cell)
        ]
    ]

    t_db = Table(db_audit_data, colWidths=[3.8 * cm, 4.3 * cm, 4.8 * cm, 4.5 * cm])
    t_db.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#102a43')),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor('#d9e2ec')),
        ('TOPPADDING', (0, 0), (-1, -1), 4),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
        ('VALIGN', (0, 0), (-1, -1), 'TOP'),
        ('ROWBACKGROUNDS', (0, 1), (-1, -1), [colors.white, colors.HexColor('#f8fafc')]),
    ]))
    story.append(t_db)
    story.append(Spacer(1, 10))

    story.append(PageBreak())

    # ─────────────────────────────────────────────────────────────────────────────
    # CAPÍTULO 4: PLAN DE REMEDIACIÓN Y HARDENING
    # ─────────────────────────────────────────────────────────────────────────────
    story.append(section_banner("4. PLAN DE REMEDIACIÓN INMEDIATA & CÓDIGO SUGERIDO", "#0d233a"))
    story.append(Spacer(1, 6))

    story.append(Paragraph(
        "Para subsanar las brechas de seguridad identificadas antes de la sustentación o pase a producción, se recomienda ejecutar el siguiente plan de remediación:",
        body_style
    ))

    remediation_steps = [
        Paragraph("<b>Paso 1: Implementar Middleware de Control de Roles (RBAC)</b><br/>"
                  "Crear el archivo <font face='Courier'>app/Http/Middleware/CheckRole.php</font> que valide: <font face='Courier'>if (!in_array(Auth::user()->idrol, $roles)) abort(403, 'Acceso Denegado');</font> "
                  "y aplicarlo en <font face='Courier'>routes/web.php</font> aislando las rutas <font face='Courier'>/users</font>, <font face='Courier'>/roles</font> para administradores (idrol=1) y <font face='Courier'>/doctores</font> para personal médico.", bullet_style),
        Paragraph("<b>Paso 2: Mitigar IDOR mediante Laravel Policies</b><br/>"
                  "Generar <font face='Courier'>PrediccionPolicy</font> y <font face='Courier'>PacientePolicy</font> vinculadas a los modelos. En <font face='Courier'>PrediccionController@show</font>, invocar "
                  "<font face='Courier'>$this->authorize('view', $prediccion);</font> para impedir que pacientes descarguen o visualicen registros ajenos.", bullet_style),
        Paragraph("<b>Paso 3: Blindar el Envío de Correos</b><br/>"
                  "En <font face='Courier'>PrediccionController@sendEmail</font>, remover el campo libre <font face='Courier'>$request->email</font> y sustituirlo por el correo del paciente registrado: "
                  "<font face='Courier'>$destinatario = $prediccion->cita->paciente->usuario->email ?? $prediccion->cita->paciente->email;</font>.", bullet_style),
        Paragraph("<b>Paso 4: Endurecimiento de Variables de Entorno en Vercel</b><br/>"
                  "En el dashboard de Vercel (Settings $\rightarrow$ Environment Variables), configurar obligatoriamente: "
                  "<font face='Courier'>APP_ENV=production</font>, <font face='Courier'>APP_DEBUG=false</font>, <font face='Courier'>SESSION_SECURE_COOKIE=true</font>, y registrar la <font face='Courier'>GEMINI_API_KEY</font> para el análisis IA.", bullet_style),
        Paragraph("<b>Paso 5: Proteger el Microservicio ML (Flask)</b><br/>"
                  "En <font face='Courier'>appml_tesis/app.py</font>, restringir el CORS: <font face='Courier'>CORS(app, resources={r'/predict': {'origins': ['https://tudominio.vercel.app']}})</font> "
                  "y validar un token de cabecera: <font face='Courier'>if request.headers.get('X-API-KEY') != os.getenv('ML_SECRET_KEY'): return jsonify({'error': 'Unauthorized'}), 401</font>.", bullet_style),
    ]

    for p in remediation_steps:
        story.append(p)
        story.append(Spacer(1, 4))

    story.append(Spacer(1, 6))

    # ─────────────────────────────────────────────────────────────────────────────
    # CAPÍTULO 5: MATRIZ DE MADUREZ Y CONCLUSIONES
    # ─────────────────────────────────────────────────────────────────────────────
    story.append(section_banner("5. MATRIZ DE MADUREZ FUNCIONAL Y CONCLUSIONES"))
    story.append(Spacer(1, 6))

    maturity_data = [
        ["Criterio Evaluado", "Calificación (1-5)", "Nivel de Madurez", "Comentarios del Auditor"],
        [
            Paragraph("<b>Flujo Clínico & Diagnóstico ML</b>", table_cell_bold),
            Paragraph("⭐⭐⭐⭐⭐ (5/5)", table_cell_center),
            Paragraph("<font color='#2e7d32'><b>Excelente</b></font>", table_cell_center),
            Paragraph("Pipeline Random Forest integrado, datos del Instrumento 3 con 70.0% de exactitud y visualización de KPIs operacionales impecable.", table_cell)
        ],
        [
            Paragraph("<b>Rendimiento & Conexión BD</b>", table_cell_bold),
            Paragraph("⭐⭐⭐⭐ (4/5)", table_cell_center),
            Paragraph("<font color='#2e7d32'><b>Robusto</b></font>", table_cell_center),
            Paragraph("El Session Pooler de Supabase evita agotamiento de sockets en entornos serverless y resuelve latencias.", table_cell)
        ],
        [
            Paragraph("<b>Arquitectura de Despliegue</b>", table_cell_bold),
            Paragraph("⭐⭐⭐⭐ (4/5)", table_cell_center),
            Paragraph("<font color='#2e7d32'><b>Adecuada</b></font>", table_cell_center),
            Paragraph("Serverless con Vercel PHP 0.7.3 y redirección a /tmp. Requiere migrar subida de avatares a almacenamiento de objetos en la nube.", table_cell)
        ],
        [
            Paragraph("<b>Seguridad & Control de Acceso</b>", table_cell_bold),
            Paragraph("⭐⭐ (2/5)", table_cell_center),
            Paragraph("<font color='#c62828'><b>Vulnerable</b></font>", table_cell_center),
            Paragraph("Falta control de roles a nivel de rutas y protección IDOR en expedientes. Debe subsanarse con las directivas del Capítulo 4.", table_cell)
        ]
    ]

    t_mat = Table(maturity_data, colWidths=[4.2 * cm, 3.2 * cm, 2.8 * cm, 7.2 * cm])
    t_mat.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#102a43')),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.HexColor('#d9e2ec')),
        ('TOPPADDING', (0, 0), (-1, -1), 4),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
        ('VALIGN', (0, 0), (-1, -1), 'TOP'),
        ('ROWBACKGROUNDS', (0, 1), (-1, -1), [colors.white, colors.HexColor('#f8fafc')]),
    ]))
    story.append(t_mat)
    story.append(Spacer(1, 10))

    story.append(Paragraph(
        "<b>Conclusión Final del Auditor:</b> El sistema <b>WebPrediccionDT2</b> posee una base funcional y matemática de excelencia para los objetivos de la tesis, "
        "con algoritmos de aprendizaje automático coherentes y datos reales plenamente consistentes con los instrumentos validados. "
        "Sin embargo, dado el carácter sensible de la información clínica, la aplicación de los parches de control de acceso (RBAC) y la desactivación de depuración "
        "en producción son imperativos para garantizar una postura de seguridad robusta y alineada con los estándares de ingeniería de software médico.",
        body_style
    ))

    # Firmas
    story.append(Spacer(1, 15))
    sign_table = Table([
        [
            Paragraph("________________________________________<br/><b>Área de Auditoría de Sistemas y Seguridad</b><br/>Ingeniería de Software & Ciberseguridad", table_cell_center),
            Paragraph("________________________________________<br/><b>Tesista / Líder de Proyecto</b><br/>Sistema WebPrediccionDT2", table_cell_center)
        ]
    ], colWidths=[8.7 * cm, 8.7 * cm])
    sign_table.setStyle(TableStyle([
        ('VALIGN', (0, 0), (-1, -1), 'TOP'),
    ]))
    story.append(sign_table)

    doc.build(story, canvasmaker=NumberedCanvas)
    print(f"Reporte generado exitosamente: {filename}")

if __name__ == "__main__":
    build_audit_pdf()
