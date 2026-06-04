<?php

return [
    'auth' => [
        'tagline'  => 'cada escolha molda seu destino',
        'create'   => 'Criar nova conta',
        'existing' => 'Já tenho uma conta',
    ],

    'nickname' => [
        'title'       => 'Como vamos te chamar?',
        'subtitle'    => 'Seu apelido é único no Petabit',
        'placeholder' => 'apelido_único',
        'available'   => 'disponível',
        'taken'       => 'já em uso',
        'continue'    => 'Continuar →',
    ],

    'email' => [
        'title'       => 'Onde te encontramos?',
        'subtitle'    => 'Para recuperar seu Petabit se precisar',
        'placeholder' => 'seu@email.com',
        'no_spam'     => 'Não enviamos spam. Nunca.',
        'continue'    => 'Continuar →',
    ],

    'verify' => [
        'title_new'      => 'Confirme seu e-mail',
        'title_existing' => 'Código de acesso',
        'sent_to'        => 'Enviamos um código para',
        'hint'           => 'Toque nos campos e digite o código',
        'resend'         => 'Reenviar código',
        'submit_new'     => 'Criar conta',
        'submit_existing' => 'Entrar',
    ],

    'welcome' => [
        'phase'    => 'Fase 1 · Nascimento',
        'title'    => 'Bem-vindo, :name!',
        'body'     => 'Seu Petabit está esperando para nascer.<br>Primeiro, vamos montar sua rotina.',
        'cta'      => 'Montar minha rotina →',
        'traveler' => 'Viajante',
    ],

    'setup' => [
        'title'            => 'Sua rotina até a próxima evolução',
        'subtitle'         => 'Evolução em 14 dias · selecione seus hábitos',
        'custom'           => 'Criar hábito personalizado',
        'new_habit'        => 'Novo hábito',
        'name_placeholder' => 'Nome do hábito...',
        'icon'             => 'Ícone',
        'add'              => 'Adicionar',
        'cancel'           => 'Cancelar',
        'selected'         => ':count hábito selecionado|:count hábitos selecionados',
        'confirm'          => 'Confirmar rotina',
    ],

    'home' => [
        'greeting'    => 'Bom dia, :name 👋',
        'day'         => 'Dia :n de :total',
        'streak'      => ':count dias',
        'phase_badge' => 'Fase :n · :stage · :days dias',
        'today'       => 'Hoje',
        'hp'          => 'Vida',
        'finish_day'  => 'Finalizar o dia ✦',
        'day_done'    => 'Dia completo ✓',
        'progress'    => ':done/:total feitos hoje',
        'dead_title'  => 'Seu Petabit partiu',
        'dead_body'   => 'Ele descansa agora. Um novo renascerá em breve.',

        'genome' => [
            'title'     => 'Genoma',
            'subtitle'  => 'Sequência ativa do seu Petabit',
            'seq_id'    => 'ID de Sequência',
            'new_genes' => 'Novos genes surgem a cada evolução',
        ],

        'merge' => [
            'title'         => 'Mesclar',
            'subtitle'      => 'Combine genomas com outro Petabit. A cada fusão seu pet morre e renasce com um traço do parceiro — para sempre.',
            'locked_badge'  => 'Bloqueado',
            'locked_title'  => 'Ainda não disponível',
            'locked_body'   => 'A mescla abre quando seu Petabit chega à <strong style="color:rgba(255,255,255,0.55);">Fase Adulta</strong> e está vivo.',
            'generate_qr'   => 'Gerar QR Code',
            'read_qr'       => 'Ler QR Code',
            'qr_title'      => 'Mostre este código',
            'qr_body'       => 'Peça para o outro jogador ler com "Ler QR Code". Válido por alguns minutos.',
            'code_or_type'  => 'ou use este código',
            'copy'          => 'Copiar',
            'cancel'        => 'Cancelar',
            'code_label'    => 'ou cole o código manualmente',
            'code_submit'   => 'Mesclar',
            'history_title' => 'Traços herdados',
            'queued'        => 'Mesclado com :name! Você herdará :part no próximo renascimento.',
            'pending_title' => 'A herdar no renascimento',
            'on_rebirth'    => 'ao renascer',
            'from_partner'  => 'Herdado de :name',
            'done_title'    => 'Mesclagem desta vida feita',
            'done_body'     => 'Você só poderá mesclar de novo quando seu Petabit renascer.',
            'err' => [
                'not_eligible'       => 'Seu Petabit precisa estar vivo e na Fase Adulta para mesclar.',
                'invalid_or_expired' => 'Código inválido ou expirado.',
                'self'               => 'Você não pode mesclar com você mesmo.',
                'generic'            => 'Não foi possível mesclar agora.',
            ],
        ],
    ],

    'question' => [
        'badge'       => 'Reflexão do dia',
        'title'       => 'Qual o sentido da vida?',
        'subtitle'    => 'Sua resposta moldará a essência do seu Petabit.',
        'placeholder' => 'Escreva o que vier à mente...',
        'submit'      => 'Revelar meu destino',
    ],

    'analyzing' => [
        'reading'   => 'lendo sua essência...',
        'no_change' => 'A essência se assenta, sem mudanças por ora.',
    ],

    'result' => [
        'alignment'     => 'Alinhamento: :label',
        'title'         => 'Seu Petabit está tomando forma',
        'reborn_title'  => 'Um novo Petabit nasceu',
        'stage_label'   => 'Fase',
        'changes_label' => 'O que mudou',
        'no_change'     => 'Nada mudou desta vez.',
        'next_phase'    => 'Próxima fase:',
        'growth'        => 'Crescimento',
        'cta'           => 'Ver evolução ✦',
        'cta_home'      => 'Voltar ao meu Petabit',
        'continue'      => 'Continuar →',
    ],

    'evolution' => [
        'phase_from'    => 'Fase 1',
        'phase_to'      => 'Fase 2',
        'title'         => 'Crescimento!',
        'body'          => '14 dias. Sua rotina formou quem seu Petabit está se tornando.',
        'summary_label' => 'Resumo da fase',
        'summary'       => '14 dias completados · 38 sessões · streak máx. 7',
        'continue'      => 'Continuar →',
    ],

    'keep' => [
        'title' => 'Quer manter a mesma rotina?',
        'body'  => 'Seus hábitos estão funcionando. Pode mantê-los ou ajustar antes da próxima evolução.',
        'active' => 'Hábitos ativos',
        'keep'  => 'Manter minha rotina',
        'edit'  => 'Editar hábitos',
    ],

    'reminders' => [
        'title'     => 'Seu Petabit te espera',
        'morning'   => 'Bom dia! Hora de cuidar dos seus hábitos ✦',
        'afternoon' => 'Boa tarde! Não esqueça seus hábitos de hoje ✦',
        'night'     => 'Última chamada — conclua seus hábitos antes de dormir ✦',
    ],

    'errors' => [
        'network'      => 'Falha de conexão. Tente de novo.',
        'email_taken'  => 'Este e-mail já está cadastrado.',
        'no_account'   => 'Nenhuma conta para este e-mail.',
        'invalid_email' => 'E-mail inválido.',
        'invalid_code' => 'Código inválido ou expirado.',
        'generic'      => 'Algo deu errado. Tente de novo.',
    ],
];
