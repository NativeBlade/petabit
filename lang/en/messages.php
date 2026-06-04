<?php

return [
    'auth' => [
        'tagline'  => 'each choice shapes your destiny',
        'create'   => 'Create new account',
        'existing' => 'I already have an account',
    ],

    'nickname' => [
        'title'       => 'What should we call you?',
        'subtitle'    => 'Your nickname is unique on Petabit',
        'placeholder' => 'unique_handle',
        'available'   => 'available',
        'taken'       => 'taken',
        'continue'    => 'Continue →',
    ],

    'email' => [
        'title'       => 'Where can we reach you?',
        'subtitle'    => 'To recover your Petabit if you ever need to',
        'placeholder' => 'you@email.com',
        'no_spam'     => 'No spam. Ever.',
        'continue'    => 'Continue →',
    ],

    'verify' => [
        'title_new'      => 'Confirm your email',
        'title_existing' => 'Access code',
        'sent_to'        => 'We sent a code to',
        'hint'           => 'Tap the fields and type the code',
        'resend'         => 'Resend code',
        'submit_new'     => 'Create account',
        'submit_existing' => 'Sign in',
    ],

    'welcome' => [
        'phase'    => 'Phase 1 · Birth',
        'title'    => 'Welcome, :name!',
        'body'     => 'Your Petabit is waiting to be born.<br>First, let’s build your routine.',
        'cta'      => 'Build my routine →',
        'traveler' => 'Traveler',
    ],

    'setup' => [
        'title'            => 'Your routine until the next evolution',
        'subtitle'         => 'Evolution in 14 days · pick your habits',
        'custom'           => 'Create custom habit',
        'new_habit'        => 'New habit',
        'name_placeholder' => 'Habit name...',
        'icon'             => 'Icon',
        'add'              => 'Add',
        'cancel'           => 'Cancel',
        'selected'         => ':count habit selected|:count habits selected',
        'confirm'          => 'Confirm routine',
    ],

    'home' => [
        'greeting'    => 'Good morning, :name 👋',
        'day'         => 'Day :n of :total',
        'streak'      => ':count days',
        'phase_badge' => 'Phase :n · :stage · :days days',
        'today'       => 'Today',
        'hp'          => 'Life',
        'finish_day'  => 'Finish the day ✦',
        'day_done'    => 'Day complete ✓',
        'progress'    => ':done/:total done today',
        'dead_title'  => 'Your Petabit has passed',
        'dead_body'   => 'It rests now. A new one will be reborn soon.',

        'genome' => [
            'title'     => 'Genome',
            'subtitle'  => 'Your Petabit’s active sequence',
            'seq_id'    => 'Sequence ID',
            'new_genes' => 'New genes emerge with every evolution',
        ],

        'merge' => [
            'title'         => 'Merge',
            'subtitle'      => 'Combine genomes with another Petabit. Each merge your pet dies and is reborn with one of the partner\'s traits — forever.',
            'locked_badge'  => 'Locked',
            'locked_title'  => 'Not available yet',
            'locked_body'   => 'Merging opens once your Petabit reaches the <strong style="color:rgba(255,255,255,0.55);">Adult Phase</strong> and is alive.',
            'generate_qr'   => 'Generate QR Code',
            'read_qr'       => 'Scan QR Code',
            'qr_title'      => 'Show this code',
            'qr_body'       => 'Have the other player scan it with "Scan QR Code". Valid for a few minutes.',
            'code_or_type'  => 'or use this code',
            'copy'          => 'Copy',
            'cancel'        => 'Cancel',
            'code_label'    => 'or paste the code manually',
            'code_submit'   => 'Merge',
            'history_title' => 'Inherited traits',
            'queued'        => 'Merged with :name! You\'ll inherit :part on the next rebirth.',
            'pending_title' => 'To inherit on rebirth',
            'on_rebirth'    => 'on rebirth',
            'from_partner'  => 'Inherited from :name',
            'done_title'    => 'Merged this life',
            'done_body'     => 'You can only merge again once your Petabit is reborn.',
            'err' => [
                'not_eligible'       => 'Your Petabit must be alive and in the Adult Phase to merge.',
                'invalid_or_expired' => 'Invalid or expired code.',
                'self'               => 'You can\'t merge with yourself.',
                'generic'            => 'Could not merge right now.',
            ],
        ],
    ],

    'question' => [
        'badge'       => 'Daily reflection',
        'title'       => 'What is the meaning of life?',
        'subtitle'    => 'Your answer will shape your Petabit’s essence.',
        'placeholder' => 'Write whatever comes to mind...',
        'submit'      => 'Reveal my destiny',
    ],

    'analyzing' => [
        'reading'   => 'reading your essence...',
        'no_change' => 'The essence settles, unchanged for now.',
    ],

    'result' => [
        'alignment'     => 'Alignment: :label',
        'title'         => 'Your Petabit is taking shape',
        'reborn_title'  => 'A new Petabit is born',
        'stage_label'   => 'Stage',
        'changes_label' => 'What changed',
        'no_change'     => 'Nothing changed this time.',
        'next_phase'    => 'Next phase:',
        'growth'        => 'Growth',
        'cta'           => 'See evolution ✦',
        'cta_home'      => 'Back to my Petabit',
        'continue'      => 'Continue →',
    ],

    'evolution' => [
        'phase_from'    => 'Phase 1',
        'phase_to'      => 'Phase 2',
        'title'         => 'Growth!',
        'body'          => '14 days. Your routine shaped who your Petabit is becoming.',
        'summary_label' => 'Phase summary',
        'summary'       => '14 days completed · 38 sessions · max streak 7',
        'continue'      => 'Continue →',
    ],

    'keep' => [
        'title' => 'Keep the same routine?',
        'body'  => 'Your habits are working. Keep them or adjust before the next evolution.',
        'active' => 'Active habits',
        'keep'  => 'Keep my routine',
        'edit'  => 'Edit habits',
    ],

    'reminders' => [
        'title' => 'Your Petabit is waiting',
        'body'  => "Don't forget today's habits ✦",
    ],

    'errors' => [
        'network'      => 'Connection failed. Try again.',
        'email_taken'  => 'This email is already registered.',
        'no_account'   => 'No account found for this email.',
        'invalid_email' => 'Invalid email.',
        'invalid_code' => 'Invalid or expired code.',
        'generic'      => 'Something went wrong. Try again.',
    ],
];
