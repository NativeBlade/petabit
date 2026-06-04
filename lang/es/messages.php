<?php

return [
    'auth' => [
        'tagline'  => 'cada elección moldea tu destino',
        'create'   => 'Crear cuenta nueva',
        'existing' => 'Ya tengo una cuenta',
    ],

    'nickname' => [
        'title'       => '¿Cómo te llamamos?',
        'subtitle'    => 'Tu apodo es único en Petabit',
        'placeholder' => 'usuario_único',
        'available'   => 'disponible',
        'taken'       => 'en uso',
        'continue'    => 'Continuar →',
    ],

    'email' => [
        'title'       => '¿Dónde podemos contactarte?',
        'subtitle'    => 'Para recuperar tu Petabit si lo necesitas',
        'placeholder' => 'tu@correo.com',
        'no_spam'     => 'Sin spam. Nunca.',
        'continue'    => 'Continuar →',
    ],

    'verify' => [
        'title_new'       => 'Confirma tu correo',
        'title_existing'  => 'Código de acceso',
        'sent_to'         => 'Enviamos un código a',
        'hint'            => 'Toca los campos y escribe el código',
        'resend'          => 'Reenviar código',
        'submit_new'      => 'Crear cuenta',
        'submit_existing' => 'Iniciar sesión',
    ],

    'welcome' => [
        'phase'    => 'Fase 1 · Nacimiento',
        'title'    => '¡Bienvenido, :name!',
        'body'     => 'Tu Petabit espera nacer.<br>Primero, construyamos tu rutina.',
        'cta'      => 'Construir mi rutina →',
        'traveler' => 'Viajero',
    ],

    'setup' => [
        'title'            => 'Tu rutina hasta la próxima evolución',
        'subtitle'         => 'Evolución en 14 días · elige tus hábitos',
        'custom'           => 'Crear hábito personalizado',
        'new_habit'        => 'Nuevo hábito',
        'name_placeholder' => 'Nombre del hábito...',
        'icon'             => 'Icono',
        'add'              => 'Agregar',
        'cancel'           => 'Cancelar',
        'selected'         => ':count hábito seleccionado|:count hábitos seleccionados',
        'confirm'          => 'Confirmar rutina',
    ],

    'home' => [
        'greeting'    => 'Buenos días, :name 👋',
        'day'         => 'Día :n de :total',
        'streak'      => ':count días',
        'phase_badge' => 'Fase :n · :stage · :days días',
        'today'       => 'Hoy',
        'hp'          => 'Vida',
        'finish_day'  => 'Terminar el día ✦',
        'day_done'    => 'Día completo ✓',
        'progress'    => ':done/:total hechos hoy',
        'dead_title'  => 'Tu Petabit ha fallecido',
        'dead_body'   => 'Ahora descansa. Pronto renacerá uno nuevo.',

        'genome' => [
            'title'     => 'Genoma',
            'subtitle'  => 'La secuencia activa de tu Petabit',
            'seq_id'    => 'ID de secuencia',
            'new_genes' => 'Surgen nuevos genes con cada evolución',
        ],

        'merge' => [
            'title'         => 'Fusionar',
            'subtitle'      => 'Combina genomas con otro Petabit. En cada fusión tu mascota muere y renace con un rasgo del compañero — para siempre.',
            'locked_badge'  => 'Bloqueado',
            'locked_title'  => 'Aún no disponible',
            'locked_body'   => 'La fusión se abre cuando tu Petabit llega a la <strong style="color:rgba(255,255,255,0.55);">Fase Adulta</strong> y está vivo.',
            'generate_qr'   => 'Generar código QR',
            'read_qr'       => 'Escanear código QR',
            'qr_title'      => 'Muestra este código',
            'qr_body'       => 'Pide al otro jugador que lo escanee con "Escanear código QR". Válido por unos minutos.',
            'code_or_type'  => 'o usa este código',
            'copy'          => 'Copiar',
            'cancel'        => 'Cancelar',
            'code_label'    => 'o pega el código manualmente',
            'code_submit'   => 'Fusionar',
            'history_title' => 'Rasgos heredados',
            'queued'        => '¡Fusionado con :name! Heredarás :part en el próximo renacimiento.',
            'pending_title' => 'A heredar al renacer',
            'on_rebirth'    => 'al renacer',
            'from_partner'  => 'Heredado de :name',
            'done_title'    => 'Fusión de esta vida hecha',
            'done_body'     => 'Solo podrás fusionar de nuevo cuando tu Petabit renazca.',
            'err' => [
                'not_eligible'       => 'Tu Petabit debe estar vivo y en la Fase Adulta para fusionar.',
                'invalid_or_expired' => 'Código inválido o expirado.',
                'self'               => 'No puedes fusionarte contigo mismo.',
                'generic'            => 'No se pudo fusionar ahora.',
            ],
        ],

        'account' => [
            'title'          => 'Cuenta',
            'username'       => 'Usuario',
            'email'          => 'Correo',
            'version'        => 'Versión',
            'support'        => 'Soporte',
            'logout'         => 'Cerrar sesión',
            'logout_confirm' => '¿Seguro que quieres cerrar sesión?',
            'delete'         => 'Eliminar mi cuenta',
            'delete_warning' => 'Tu cuenta quedará marcada para eliminación y se borrará en 30 días. Solo inicia sesión de nuevo en ese plazo para cancelar.',
            'cancel'         => 'Cancelar',
            'delete_confirm' => 'Eliminar',
        ],
    ],

    'question' => [
        'badge'       => 'Reflexión diaria',
        'title'       => '¿Cuál es el sentido de la vida?',
        'subtitle'    => 'Tu respuesta moldeará la esencia de tu Petabit.',
        'placeholder' => 'Escribe lo que se te ocurra...',
        'submit'      => 'Revelar mi destino',
    ],

    'analyzing' => [
        'reading'   => 'leyendo tu esencia...',
        'no_change' => 'La esencia se asienta, sin cambios por ahora.',
    ],

    'result' => [
        'alignment'     => 'Alineación: :label',
        'title'         => 'Tu Petabit está tomando forma',
        'reborn_title'  => 'Nace un nuevo Petabit',
        'stage_label'   => 'Etapa',
        'changes_label' => 'Qué cambió',
        'no_change'     => 'Nada cambió esta vez.',
        'next_phase'    => 'Próxima fase:',
        'growth'        => 'Crecimiento',
        'cta'           => 'Ver evolución ✦',
        'cta_home'      => 'Volver a mi Petabit',
        'continue'      => 'Continuar →',
    ],

    'evolution' => [
        'phase_from'    => 'Fase 1',
        'phase_to'      => 'Fase 2',
        'title'         => '¡Crecimiento!',
        'body'          => '14 días. Tu rutina moldeó en quién se está convirtiendo tu Petabit.',
        'summary_label' => 'Resumen de la fase',
        'summary'       => '14 días completados · 38 sesiones · racha máx. 7',
        'continue'      => 'Continuar →',
    ],

    'keep' => [
        'title'  => '¿Mantener la misma rutina?',
        'body'   => 'Tus hábitos están funcionando. Mantenlos o ajústalos antes de la próxima evolución.',
        'active' => 'Hábitos activos',
        'keep'   => 'Mantener mi rutina',
        'edit'   => 'Editar hábitos',
    ],

    'reminders' => [
        'title' => 'Tu Petabit te espera',
        'body'  => 'No olvides tus hábitos de hoy ✦',
    ],

    'errors' => [
        'network'      => 'Falló la conexión. Inténtalo de nuevo.',
        'email_taken'  => 'Este correo ya está registrado.',
        'no_account'   => 'No se encontró una cuenta para este correo.',
        'invalid_email' => 'Correo inválido.',
        'invalid_code' => 'Código inválido o expirado.',
        'generic'      => 'Algo salió mal. Inténtalo de nuevo.',
    ],
];
