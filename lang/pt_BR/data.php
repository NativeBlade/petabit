<?php

return [
    'alignment' => [
        'good'         => 'Luz Pura',
        'good-neutral' => 'Luz',
        'neutral'      => 'Neutro',
        'evil-neutral' => 'Sombra',
        'evil'         => 'Trevas',
    ],

    'gene_kind' => [
        'herdado'     => 'herdado',
        'mutação'     => 'mutação',
        'alinhamento' => 'alinhamento',
        'base'        => 'base',
    ],

    'tab' => [
        'petabit' => 'Petabit',
        'genoma'  => 'Genoma',
        'mesclar' => 'Mesclar',
    ],

    'stage' => [
        'Birth'  => 'Nascimento',
        'Origin' => 'Origem',
        'Growth' => 'Crescimento',
        'Adult'  => 'Adulta',
        'Elder'  => 'Velha',
        'Death'  => 'Morte',
    ],

    'gene_part' => [
        'body'  => 'Forma Corporal',
        'eyes'  => 'Olhos',
        'wings' => 'Asas',
        'ears'  => 'Orelhas',
        'tail'  => 'Cauda',
        'aura'  => 'Aura',
        'arms'  => 'Braços',
        'legs'  => 'Patas',
        'snout' => 'Focinho',
        'pattern' => 'Pelagem',
    ],

    'rarity' => [
        'common'    => 'Comum',
        'uncommon'  => 'Incomum',
        'rare'      => 'Raro',
        'legendary' => 'Lendário',
        'base'      => 'Base',
    ],

    /* valores de gene (keys do catálogo do servidor) */
    'gene_value' => [
        'none' => 'Nenhum', 'neutral' => 'Neutra', 'round' => 'Redonda', 'oval' => 'Oval', 'egg' => 'Ovo', 'fluffy' => 'Fofinho',
        'blob' => 'Bolinha', 'long' => 'Comprido', 'pear' => 'Pêra', 'bighead' => 'Cabeçudo', 'bird' => 'Passarinho',
        'jelly' => 'Geleia', 'mushroom' => 'Cogumelo', 'cloud' => 'Nuvem', 'pudding' => 'Pudim', 'penguin' => 'Pinguim',
        'doll' => 'Boneco', 'drop' => 'Gotinha', 'droplet' => 'Gotinha', 'ghost' => 'Fantasma', 'quadruped' => 'Quadrúpede',
        'snake' => 'Cobrinha', 'cactus' => 'Cacto', 'dino' => 'Dino', 'star' => 'Estrela', 'heart' => 'Coração', 'moon' => 'Lua',
        'feathers' => 'Penas', 'butterfly' => 'Borboleta', 'bat' => 'Morcego', 'fairy' => 'Fada', 'dragonfly' => 'Libélula',
        'leaf' => 'Folha', 'angel' => 'Anjo', 'demon' => 'Demônio', 'crystal' => 'Cristal', 'dragon' => 'Dragão', 'phoenix' => 'Fênix',
        'cat' => 'Gato', 'bear' => 'Urso', 'floppy' => 'Caída', 'pointed' => 'Pontuda', 'rabbit' => 'Coelho', 'fox' => 'Raposa',
        'horn' => 'Chifrinho', 'unicorn' => 'Unicórnio', 'horns' => 'Chifrões',
        'curl' => 'Enroladinho', 'poof' => 'Pompom', 'kitten' => 'Gatinho', 'tuft' => 'Tufo', 'plume' => 'Penacho',
        'spike' => 'Espinho', 'mermaid' => 'Sereia', 'bolt' => 'Raio', 'flames' => 'Chamas', 'imp' => 'Diabinho',
        'paw' => 'Patinha', 'claw' => 'Garrinha', 'tentacle' => 'Tentáculo', 'boot' => 'Botinha',
        'beak' => 'Bico', 'long_beak' => 'Bico longo', 'snout' => 'Focinho',
        'smoke' => 'Fumaça', 'dust' => 'Poeira', 'light' => 'Luz', 'energy' => 'Energia', 'bubbles' => 'Bolhas',
        'petals' => 'Pétalas', 'stars' => 'Estrelas', 'fire' => 'Fogo', 'shadow' => 'Sombra', 'lightning' => 'Relâmpago',
        'neon' => 'Neon', 'ice' => 'Gelo', 'poison' => 'Veneno', 'holy' => 'Sagrada', 'galaxy' => 'Galáxia',
        'aurora' => 'Aurora', 'vortex' => 'Vórtice', 'rainbow' => 'Arco-íris',
        'night' => 'Noite', 'cocoa' => 'Cacau', 'blue' => 'Azul', 'amber' => 'Âmbar', 'green' => 'Verde',
        'crimson' => 'Carmim', 'purple' => 'Roxo', 'gold' => 'Ouro', 'pink' => 'Rosa', 'turquoise' => 'Turquesa',
    ],

    /* templates de mudança da evolução (:value resolve via gene_value/pattern/mark) */
    'change' => [
        'eyes' => 'olhos +1 (:count)',
        'legs_appear' => 'perninhas surgem', 'legs_evolve' => 'patas evoluem (:value)',
        'arms_appear' => 'bracinhos surgem', 'arms_evolve' => 'braços evoluem (:value)',
        'ears' => 'orelhas: :value', 'tail' => 'cauda: :value', 'snout' => 'focinho/bico: :value',
        'wing_one' => 'asa surge (um lado: :value)', 'wing_pair' => 'par de asas completo', 'wing_evolve' => 'asas evoluem (:value)',
        'aura_awaken' => 'aura desperta (:value)', 'aura_evolve' => 'aura evolui (:value)', 'aura_intensify' => 'aura intensifica',
        'antenna' => 'antena', 'coat' => 'pelagem: :value', 'mark' => 'marca: :value',
    ],
    'pattern' => [
        'none' => 'Liso', 'freckles' => 'Sardas', 'spots' => 'Pintas', 'stripes' => 'Listras', 'patches' => 'Manchas',
    ],
    'mark' => [
        'scar' => 'Cicatriz', 'crack' => 'Racha', 'patch' => 'Remendo', 'third_eye' => '3º olho', 'heart_mark' => 'Sinal de coração', 'star_mark' => 'Sinal de estrela',
    ],

    'weekday_short' => [
        1 => 'Seg', 2 => 'Ter', 3 => 'Qua', 4 => 'Qui', 5 => 'Sex', 6 => 'Sáb', 7 => 'Dom',
    ],

    'habits' => [
        'water'       => 'Água',
        'study'       => 'Estudar',
        'train'       => 'Treinar',
        'sleep'       => 'Dormir bem',
        'meditate'    => 'Meditar',
        'eat_well'    => 'Comer bem',
        'read'        => 'Ler',
        'write'       => 'Escrever',
        'walk'        => 'Caminhar',
        'stretch'     => 'Alongar',
        'art'         => 'Arte',
        'supplements' => 'Suplementos',
        'tidy'        => 'Organizar espaço',
        'finances'    => 'Finanças',
        'no_screen'   => 'Sem tela à noite',
        'outdoors'    => 'Ar livre',
    ],

    'genome' => [
        'body'   => ['name' => 'Forma Corporal',    'trait' => 'Blob Lunar'],
        'eyes'   => ['name' => 'Tipo de Olhos',     'trait' => 'Redondo Brilhante'],
        'aura'   => ['name' => 'Aura',              'trait' => 'Âmbar Suave'],
        'voice'  => ['name' => 'Voz Interior',      'trait' => 'Sussurro Guia'],
        'core'   => ['name' => 'Núcleo',            'trait' => 'Neutro Primordial'],
        'habits' => ['name' => 'Padrão de Hábitos', 'trait' => 'Constante Leve'],
        'sleep'  => ['name' => 'Ciclo de Sono',     'trait' => 'Noturno'],
        'social' => ['name' => 'Instinto Social',   'trait' => 'Independente'],
    ],

    'partner' => [
        'meta'      => 'Fase 3 · Crescimento · Alinhamento Sombra',
        'badge'     => 'Sombra',
        'merged_at' => 'Mesclado em 12 jan 2025 · irreversível',
    ],

    'merge_traits' => [
        'social' => ['name' => 'Instinto Social', 'from' => 'Independente', 'to' => 'Expansivo'],
        'aura'   => ['name' => 'Aura',            'from' => 'Âmbar Suave',  'to' => 'Violeta Crua'],
        'sleep'  => ['name' => 'Ciclo de Sono',   'from' => 'Noturno',      'to' => 'Aurora'],
    ],
];
