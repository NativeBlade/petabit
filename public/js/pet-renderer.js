/* =========================================================================
   Petabit genome renderer — buildPet() + paintAura() ported from
   petabit-playground.html, adapted to consume the server's (English-id)
   genome JSON and render into passed DOM elements.

   Exposes window.PetabitRenderer.renderPet(genome, holderEl, auraEl, haloEl).
   The aura uses tsParticles (loaded globally); if it's unavailable the SVG
   creature still renders (the aura is purely additive).
   ========================================================================= */
(function () {
  'use strict';

  /* ---- palettes / colors (English keys, matching the server Catalog) ---- */
  var PAL = {
    cream:    { m: '#F1E6CC', d: '#c4b083', l: '#FAF3E2' },
    butter:   { m: '#F6D26B', d: '#c79a2a', l: '#FCEBAE' },
    peach:    { m: '#F4B49B', d: '#cf7f5e', l: '#FAD6C6' },
    mint:     { m: '#A4DEBD', d: '#5fa97f', l: '#CCEEDB' },
    sky:      { m: '#A6CBEF', d: '#5e8fc4', l: '#CEE2F6' },
    lavender: { m: '#C7B4E6', d: '#8f73c0', l: '#DFD2F1' },
    rose:     { m: '#F1B3C6', d: '#cf7593', l: '#F7D1DD' },
    gray:     { m: '#C2C6CF', d: '#868c98', l: '#DDE0E5' },
    snow:     { m: '#F2F4F8', d: '#b9c0cc', l: '#FFFFFF' },
    golden:   { m: '#E8C566', d: '#a8861f', l: '#F6E6AE' },
    wine:     { m: '#9E5566', d: '#5e2c3a', l: '#C58294' },
    charcoal: { m: '#5A5566', d: '#322f3c', l: '#807a8c' },
    petrol:   { m: '#5E8C92', d: '#34565a', l: '#8FB6BB' },
    amethyst: { m: '#9B7BC2', d: '#5e4486', l: '#C4ABE0' },
  };
  var EYE = {
    night: '#3A3340', cocoa: '#5A4632', blue: '#3F6FA3', amber: '#C68A2E',
    green: '#4E8C5A', crimson: '#9E3B4E', purple: '#6A4A9E', ice: '#9FCBE8',
    fire: '#D9722E', gold: '#D4A82E', pink: '#D86A9E', turquoise: '#2EA6A6',
  };
  var ACC = {
    blush: '#F19BA6', coral: '#EF8268', gold: '#F2C94C', mint: '#8FE0BA',
    sky: '#8FC2F2', lilac: '#C9A8E8', crimson: '#C95B6E', ice: '#BFE3F2',
  };

  /* ---- wing silhouettes (local coords, origin at the socket) ---- */
  var WING = {
    neutral:   'M0,0 C-14,-24 -34,-28 -38,-13 C-34,-2 -16,2 -2,-2 Z',
    feathers:  'M0,0 C-12,-30 -32,-44 -46,-38 C-38,-30 -44,-26 -34,-24 C-46,-20 -42,-12 -32,-12 C-42,-6 -36,3 -26,-2 C-32,7 -22,8 -16,1 Z',
    angel:     'M0,0 C-10,-40 -36,-58 -54,-50 C-44,-40 -52,-34 -40,-32 C-54,-27 -48,-15 -36,-15 C-48,-8 -40,5 -28,-2 C-36,9 -22,12 -16,2 C-12,10 -5,6 -5,-6 Z',
    butterfly: 'M0,0 C-32,-34 -56,-18 -46,4 C-52,18 -30,24 -16,8 C-22,20 -4,18 -2,4 Z',
    bat:       'M0,0 L-42,-34 L-37,-21 L-28,-26 L-24,-12 L-15,-18 L-11,-4 L-3,-9 Z',
    demon:     'M0,0 L-50,-44 L-43,-26 L-32,-32 L-28,-15 L-17,-22 L-12,-4 L-3,-11 Z',
    dragon:    'M0,-6 L-8,-30 L-14,-12 L-54,-48 L-47,-28 L-35,-34 L-31,-15 L-19,-23 L-13,-3 L-3,-10 Z',
    crystal:   'M0,0 L-16,-30 L-10,-12 L-30,-40 L-24,-16 L-44,-34 L-36,-12 L-18,-6 Z',
    fairy:     'M0,0 C-20,-26 -42,-16 -36,2 C-44,8 -36,22 -22,16 C-26,26 -10,26 -8,12 C-2,18 4,10 0,0 Z',
    dragonfly: 'M0,-2 C-24,-12 -50,-12 -54,-5 C-50,2 -24,0 -2,2 Z M0,3 C-20,-1 -42,4 -46,11 C-40,16 -20,10 -2,6 Z',
    leaf:      'M0,0 C-8,-24 -36,-30 -48,-18 C-42,-4 -18,-2 -2,-2 Z',
    phoenix:   'M0,-4 L-10,-28 L-15,-12 L-32,-42 L-27,-22 L-46,-46 L-40,-24 L-56,-38 L-46,-16 L-32,-13 L-16,-7 Z',
    heart:     'M-2,-2 C-6,-22 -28,-24 -30,-8 C-31,2 -22,8 -12,4 C-16,12 -4,16 -2,2 C0,12 6,4 2,-4 Z',
    ice:       'M0,0 L-12,-26 L-8,-10 L-26,-30 L-20,-12 L-38,-26 L-30,-9 L-44,-20 L-36,-7 L-18,-4 Z',
  };

  /* ---- small helpers (id-agnostic, verbatim) ---- */
  function darken(hex, f) {
    var n = parseInt(hex.slice(1), 16), r = (n >> 16) & 255, g = (n >> 8) & 255, b = n & 255;
    return '#' + ((1 << 24) + (Math.round(r * (1 - f)) << 16) + (Math.round(g * (1 - f)) << 8) + Math.round(b * (1 - f))).toString(16).slice(1);
  }
  function hexA(hex, al) {
    var n = parseInt(hex.slice(1), 16);
    return 'rgba(' + ((n >> 16) & 255) + ',' + ((n >> 8) & 255) + ',' + (n & 255) + ',' + al + ')';
  }
  function scaleAbout(frag, px, py, s) {
    return '<g transform="translate(' + px + ',' + py + ') scale(' + s + ') translate(' + (-px) + ',' + (-py) + ')">' + frag + '</g>';
  }
  function star(cx, cy, r, c) {
    var p = '';
    for (var i = 0; i < 10; i++) { var ang = -Math.PI / 2 + i * Math.PI / 5, rr = i % 2 ? r * 0.45 : r; p += (i ? 'L' : 'M') + (cx + Math.cos(ang) * rr).toFixed(1) + ',' + (cy + Math.sin(ang) * rr).toFixed(1) + ' '; }
    return '<path d="' + p + 'Z" fill="' + c + '"/>';
  }
  function rng(seed) { var t = (seed >>> 0) || 1; return function () { t += 0x6D2B79F5; var r = Math.imul(t ^ t >>> 15, 1 | t); r ^= r + Math.imul(r ^ r >>> 7, 61 | t); return ((r ^ r >>> 14) >>> 0) / 4294967296; }; }
  function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }

  function band(a) { return a <= -34 ? 'evil' : a >= 34 ? 'good' : 'neutral'; }
  function intensity(G) { return Math.abs(G.alignment) / 100; }
  function wingScale(G) { return 1 + intensity(G) * 0.75; }
  function hornScale(G) { return 1 + intensity(G) * 0.60; }
  function tailScale(G) { return 1 + intensity(G) * 0.50; }

  function wingG(x, y, flip, type, color, s) {
    if (type === 'none') return '';
    var st = darken(color, 0.32), sx = (flip ? -s : s), tr = 'translate(' + x + ',' + y + ') scale(' + sx + ',' + s + ')';
    return '<path transform="' + tr + '" d="' + WING[type] + '" fill="' + color + '" stroke="' + st + '" stroke-width="' + (2 / s).toFixed(2) + '" stroke-linejoin="round"/>';
  }

  /* ---- one eye, by style + color ---- */
  function drawEye(x, eY, es, col, style, glow) {
    var G_ = glow ? '<circle cx="' + x + '" cy="' + eY + '" r="' + (es * 1.9).toFixed(1) + '" fill="' + glow + '" opacity="0.28"/><circle cx="' + x + '" cy="' + eY + '" r="' + (es * 1.35).toFixed(1) + '" fill="' + glow + '" opacity="0.3"/>' : '';
    if (style === 'happy') return '<path d="M' + (x - es * 0.9) + ',' + (eY + es * 0.2) + ' Q' + x + ',' + (eY - es * 0.95) + ' ' + (x + es * 0.9) + ',' + (eY + es * 0.2) + '" fill="none" stroke="' + col + '" stroke-width="3.5" stroke-linecap="round"/>';
    if (style === 'sleepy') return '<path d="M' + (x - es) + ',' + eY + ' A' + es + ',' + es + ' 0 0 0 ' + (x + es) + ',' + eY + ' Z" fill="' + col + '"/><line x1="' + (x - es) + '" y1="' + eY + '" x2="' + (x + es) + '" y2="' + eY + '" stroke="' + col + '" stroke-width="2.5" stroke-linecap="round"/>';
    if (style === 'star') return G_ + star(x, eY, es * 1.15, col) + '<circle cx="' + (x + es * 0.22) + '" cy="' + (eY - es * 0.22) + '" r="' + (es * 0.24) + '" fill="#fff"/>';
    if (style === 'cat') return G_ + '<ellipse cx="' + x + '" cy="' + eY + '" rx="' + (es * 0.78) + '" ry="' + es + '" fill="' + col + '"/><ellipse cx="' + x + '" cy="' + eY + '" rx="' + (es * 0.22) + '" ry="' + (es * 0.82) + '" fill="#1a1620"/><circle cx="' + (x + es * 0.28) + '" cy="' + (eY - es * 0.34) + '" r="' + (es * 0.3) + '" fill="#fff"/>';
    var rxE = style === 'almond' ? es * 0.82 : es;
    return G_ + '<ellipse cx="' + x + '" cy="' + eY + '" rx="' + rxE + '" ry="' + es + '" fill="' + col + '"/><circle cx="' + (x + es * 0.32) + '" cy="' + (eY - es * 0.34) + '" r="' + (es * 0.42) + '" fill="#fff"/><circle cx="' + (x - es * 0.34) + '" cy="' + (eY + es * 0.3) + '" r="' + (es * 0.16) + '" fill="#fff" opacity="0.8"/>';
  }

  /* ---- rig: archetype anchors per body.type ---- */
  function rig(G) {
    var t = (G.body && G.body.type) || 'blob', sh = G.body.shape, cx = 150;
    var rx = 50, ry = 52, cy = 178;
    if (sh === 'egg') { rx = 45; ry = 58; cy = 176; }
    else if (sh === 'fluffy') { rx = 57; ry = 47; cy = 182; }
    else if (sh === 'drop') { rx = 47; ry = 56; cy = 178; }
    var ell = function (x, y, a, b, bc, bd) { return '<ellipse cx="' + x + '" cy="' + y + '" rx="' + a + '" ry="' + b + '" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>'; };
    var R = {
      type: t, cx: cx, rx: rx, ry: ry, cy: cy,
      headCx: cx, headTop: cy - ry + 6, faceCy: cy - ry * 0.30, headW: 1, headHalf: rx,
      wingY: cy - 16, legY: cy + ry - 2, legXs: [131, 169],
      arms: true, armY: cy - 2, armLx: cx - rx, armRx: cx + rx, tailX: cx + rx, tailY: cy + ry,
      drawBody: function (bc, bd) {
        return sh === 'drop'
          ? '<path d="M' + cx + ',' + (cy - ry) + ' C' + (cx - rx) + ',' + (cy - ry + 12) + ' ' + (cx - rx) + ',' + (cy + ry) + ' ' + cx + ',' + (cy + ry) + ' C' + (cx + rx) + ',' + (cy + ry) + ' ' + (cx + rx) + ',' + (cy - ry + 12) + ' ' + cx + ',' + (cy - ry) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>'
          : ell(cx, cy, rx, ry, bc, bd);
      },
    };

    if (t === 'long') { var RX = 42, RY = 72, CY = 170; Object.assign(R, { rx: RX, ry: RY, cy: CY, headTop: CY - RY + 8, faceCy: CY - RY * 0.46, headW: 0.9, headHalf: RX, wingY: CY - 34, legY: CY + RY - 2, legXs: [138, 162], armY: CY + 10, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX, tailY: CY + RY, drawBody: function (bc, bd) { return ell(cx, CY, RX, RY, bc, bd); } }); }
    else if (t === 'bighead') { var BRX = 40, BRY = 34, BCY = 214, HR = 44, HCY = 150; Object.assign(R, { rx: BRX, ry: BRY, cy: BCY, headCx: cx, headTop: HCY - HR + 4, faceCy: HCY + 6, headW: 0.92, headHalf: HR, wingY: BCY - BRY - 2, legY: BCY + BRY - 2, legXs: [135, 165], armY: BCY - 4, armLx: cx - BRX, armRx: cx + BRX, tailX: cx + BRX, tailY: BCY + BRY, drawBody: function (bc, bd) { return ell(cx, BCY, BRX, BRY, bc, bd) + '<path d="M' + (cx - 11) + ',' + (HCY + HR - 8) + ' Q' + cx + ',' + (HCY + HR + 10) + ' ' + (cx + 11) + ',' + (HCY + HR - 8) + ' Z" fill="' + bc + '"/>' + ell(cx, HCY, HR, HR - 2, bc, bd); } }); }
    else if (t === 'bird') { var RX = 44, RY = 56, CY = 182; Object.assign(R, { rx: RX, ry: RY, cy: CY, headTop: CY - RY + 6, faceCy: CY - RY * 0.36, headW: 0.9, headHalf: RX, wingY: CY - 20, legY: CY + RY - 2, legXs: [140, 160], armY: CY - 2, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX, tailY: CY + RY, drawBody: function (bc, bd) { return ell(cx, CY, RX, RY, bc, bd); } }); }
    else if (t === 'jelly') { var RX = 50, RY = 48, CY = 180, rad = 18, L = cx - RX, T = CY - RY, hh = 2 * RX - 2 * rad, vv = 2 * RY - 2 * rad; var rr = 'M' + (L + rad) + ',' + T + ' h' + hh + ' a' + rad + ',' + rad + ' 0 0 1 ' + rad + ',' + rad + ' v' + vv + ' a' + rad + ',' + rad + ' 0 0 1 -' + rad + ',' + rad + ' h-' + hh + ' a' + rad + ',' + rad + ' 0 0 1 -' + rad + ',-' + rad + ' v-' + vv + ' a' + rad + ',' + rad + ' 0 0 1 ' + rad + ',-' + rad + ' z'; Object.assign(R, { rx: RX, ry: RY, cy: CY, headTop: CY - RY + 8, faceCy: CY - RY * 0.16, headW: 1, headHalf: RX, wingY: CY - 16, legY: CY + RY - 2, legXs: [128, 172], armY: CY - 2, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX, tailY: CY + RY, drawBody: function (bc, bd) { return '<path d="' + rr + '" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>'; } }); }
    else if (t === 'ghost') { var RX = 46, RY = 54, CY = 176; Object.assign(R, { rx: RX, ry: RY, cy: CY, headTop: CY - RY + 6, faceCy: CY - RY * 0.20, headW: 1, headHalf: RX, wingY: CY - 18, legY: CY + RY - 2, legXs: [], armY: CY - 2, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX, tailY: CY + RY, drawBody: function (bc, bd) { var L = cx - RX, Rr = cx + RX, T = CY - RY, B = CY + RY - 6, shY = T + 28, w = (2 * RX) / 4; var d = 'M' + L + ',' + shY + ' Q' + L + ',' + T + ' ' + cx + ',' + T + ' Q' + Rr + ',' + T + ' ' + Rr + ',' + shY + ' L' + Rr + ',' + B; for (var i = 0; i < 4; i++) { d += ' q' + (-w / 2) + ',' + (i % 2 === 0 ? 12 : -12) + ' ' + (-w) + ',0'; } return '<path d="' + d + ' L' + L + ',' + shY + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>'; } }); }
    else if (t === 'quadruped') { var RX = 66, RY = 38, CY = 192; Object.assign(R, { rx: RX, ry: RY, cy: CY, headCx: cx, headTop: CY - RY + 2, faceCy: CY - RY * 0.05, headW: 1.05, headHalf: 48, wingY: CY - 20, legY: CY + RY - 2, legXs: [112, 140, 160, 188], arms: false, armY: CY, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX - 6, tailY: CY + RY - 6, drawBody: function (bc, bd) { return ell(cx, CY, RX, RY, bc, bd); } }); }
    else if (t === 'pear') { var RX = 58, CY = 196, TY = 124, BY = 232; Object.assign(R, { rx: RX, ry: 54, cy: CY, headTop: 130, faceCy: 168, headW: 0.9, headHalf: 44, wingY: 178, legY: 228, legXs: [134, 166], armY: 190, armLx: cx - 46, armRx: cx + 46, tailX: cx + 46, tailY: 230, drawBody: function (bc, bd) { return '<path d="M' + cx + ',' + TY + ' C96,150 92,' + BY + ' ' + cx + ',' + BY + ' C208,' + BY + ' 204,150 ' + cx + ',' + TY + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>'; } }); }
    else if (t === 'mushroom') { var SRX = 24, SRY = 34, SCY = 214, CAPY = 150, CAPRX = 54, CAPRY = 36; Object.assign(R, { rx: SRX, ry: SRY, cy: SCY, headCx: cx, headTop: CAPY - CAPRY + 4, faceCy: CAPY + 2, headW: 1, headHalf: 48, wingY: 196, legY: SCY + SRY - 2, legXs: [138, 162], armY: SCY - 6, armLx: cx - SRX, armRx: cx + SRX, tailX: cx + SRX, tailY: SCY + SRY, drawBody: function (bc, bd) { return ell(cx, SCY, SRX, SRY, bc, bd) + ell(cx, CAPY, CAPRX, CAPRY, bc, bd) + '<ellipse cx="' + (cx - 22) + '" cy="' + (CAPY - 6) + '" rx="9" ry="7" fill="' + darken(bc, 0.06) + '"/><ellipse cx="' + (cx + 20) + '" cy="' + (CAPY + 2) + '" rx="7" ry="5" fill="' + darken(bc, 0.06) + '"/>'; } }); }
    else if (t === 'cloud') { var RX = 56, CY = 184; Object.assign(R, { rx: RX, ry: 30, cy: CY, headTop: 150, faceCy: CY, headW: 1, headHalf: 46, wingY: 166, legY: CY + 28, legXs: [], arms: false, armY: CY, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX, tailY: CY + 24, drawBody: function (bc, bd) { return ell(cx, CY, RX, 30, bc, bd) + '<circle cx="116" cy="170" r="24" fill="' + bc + '"/><circle cx="' + cx + '" cy="158" r="30" fill="' + bc + '"/><circle cx="186" cy="172" r="22" fill="' + bc + '"/>'; } }); }
    else if (t === 'snake') { var HCY = 120, HR = 30; var sp = 'M150,252 C124,230 178,206 150,182 C122,158 176,146 150,' + (HCY + 18); Object.assign(R, { rx: 16, ry: 50, cy: 206, headCx: cx, headTop: HCY - HR + 4, faceCy: HCY, headW: 0.62, headHalf: HR, wingY: 180, legY: 250, legXs: [], arms: false, armY: 200, armLx: cx - 30, armRx: cx + 30, tailX: cx + 24, tailY: 248, drawBody: function (bc, bd) { return '<path d="' + sp + '" fill="none" stroke="' + bd + '" stroke-width="38" stroke-linecap="round"/><path d="' + sp + '" fill="none" stroke="' + bc + '" stroke-width="32" stroke-linecap="round"/>' + ell(cx, HCY, HR, HR - 2, bc, bd); } }); }
    else if (t === 'star') { var CY = 180, OR = 66, IR = 28; Object.assign(R, { rx: 40, ry: 40, cy: CY, headTop: 150, faceCy: 184, headW: 0.7, headHalf: 34, wingY: 158, legY: CY + OR, legXs: [], arms: false, armY: CY, armLx: cx - 40, armRx: cx + 40, tailX: cx + 40, tailY: CY + 30, drawBody: function (bc, bd) { var p = ''; for (var i = 0; i < 10; i++) { var ang = -Math.PI / 2 + i * Math.PI / 5, rr = i % 2 ? IR : OR; p += (i ? 'L' : 'M') + (cx + Math.cos(ang) * rr).toFixed(1) + ',' + (CY + Math.sin(ang) * rr).toFixed(1) + ' '; } return '<path d="' + p + 'Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>'; } }); }
    else if (t === 'pudding') { var CY = 196, TT = 152, BB = 234; Object.assign(R, { rx: 50, ry: 42, cy: CY, headTop: 158, faceCy: 186, headW: 0.9, headHalf: 42, wingY: 176, legY: 232, legXs: [134, 166], armY: 192, armLx: cx - 46, armRx: cx + 46, tailX: cx + 46, tailY: 230, drawBody: function (bc, bd) { return '<path d="M' + (cx - 38) + ',' + TT + ' L' + (cx + 38) + ',' + TT + ' L' + (cx + 54) + ',' + (BB - 8) + ' Q' + (cx + 54) + ',' + BB + ' ' + (cx + 46) + ',' + BB + ' L' + (cx - 46) + ',' + BB + ' Q' + (cx - 54) + ',' + BB + ' ' + (cx - 54) + ',' + (BB - 8) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>' + '<path d="M' + (cx - 40) + ',' + (TT + 2) + ' Q' + cx + ',' + (TT - 12) + ' ' + (cx + 40) + ',' + (TT + 2) + ' Q' + (cx + 30) + ',' + (TT + 14) + ' ' + (cx + 12) + ',' + (TT + 6) + ' Q' + cx + ',' + (TT + 16) + ' ' + (cx - 14) + ',' + (TT + 6) + ' Q' + (cx - 30) + ',' + (TT + 14) + ' ' + (cx - 40) + ',' + (TT + 2) + ' Z" fill="' + darken(bc, 0.2) + '"/>'; } }); }
    else if (t === 'cactus') { var CY = 182, RX = 32, RY = 62; Object.assign(R, { rx: RX, ry: RY, cy: CY, headTop: CY - RY + 8, faceCy: CY - RY * 0.34, headW: 0.85, headHalf: RX, wingY: CY - 30, legY: CY + RY - 2, legXs: [], arms: false, armY: CY, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX, tailY: CY + RY, drawBody: function (bc, bd) { var rad = RX; var spn = ''; for (var k = 0; k < 7; k++) { var yy = (CY - RY + 14) + k * ((RY * 1.6) / 7); spn += '<path d="M' + cx + ',' + yy.toFixed(1) + ' l5,-2 l-5,-2" fill="none" stroke="' + darken(bc, 0.3) + '" stroke-width="1.2"/>'; } var main = '<path d="M' + (cx - RX) + ',' + (CY - RY + rad) + ' a' + rad + ',' + rad + ' 0 0 1 ' + (2 * RX) + ',0 v' + (2 * RY - 2 * rad) + ' a' + rad + ',' + rad + ' 0 0 1 -' + (2 * RX) + ',0 z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>'; var lobL = '<path d="M' + (cx - RX + 4) + ',' + (CY - 6) + ' q-22,-4 -22,-22 q0,-12 12,-10 q10,2 10,16 z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>'; var lobR = '<path d="M' + (cx + RX - 4) + ',' + (CY + 6) + ' q22,-4 22,-22 q0,-12 -12,-10 q-10,2 -10,16 z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>'; return lobL + lobR + main + spn; } }); }
    else if (t === 'heart') { var CY = 178; Object.assign(R, { rx: 52, ry: 46, cy: CY, headTop: 142, faceCy: 172, headW: 0.85, headHalf: 44, wingY: 160, legY: CY + 44, legXs: [136, 164], armY: 178, armLx: cx - 50, armRx: cx + 50, tailX: cx + 46, tailY: CY + 34, drawBody: function (bc, bd) { return '<path d="M' + cx + ',' + (CY + 48) + ' C' + (cx - 12) + ',' + (CY + 30) + ' ' + (cx - 58) + ',' + (CY + 6) + ' ' + (cx - 58) + ',' + (CY - 22) + ' C' + (cx - 58) + ',' + (CY - 52) + ' ' + (cx - 18) + ',' + (CY - 54) + ' ' + cx + ',' + (CY - 24) + ' C' + (cx + 18) + ',' + (CY - 54) + ' ' + (cx + 58) + ',' + (CY - 52) + ' ' + (cx + 58) + ',' + (CY - 22) + ' C' + (cx + 58) + ',' + (CY + 6) + ' ' + (cx + 12) + ',' + (CY + 30) + ' ' + cx + ',' + (CY + 48) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>'; } }); }
    else if (t === 'droplet') { var CY = 196, R0 = 46, TP = 118; Object.assign(R, { rx: R0, ry: 48, cy: CY, headTop: 160, faceCy: CY, headW: 0.95, headHalf: R0, wingY: 180, legY: CY + R0 - 2, legXs: [136, 164], armY: CY, armLx: cx - R0, armRx: cx + R0, tailX: cx + R0, tailY: CY + R0, drawBody: function (bc, bd) { return '<path d="M' + cx + ',' + TP + ' C' + (cx - 24) + ',150 ' + (cx - R0) + ',' + (CY - 22) + ' ' + (cx - R0) + ',' + CY + ' A' + R0 + ',' + R0 + ' 0 1 0 ' + (cx + R0) + ',' + CY + ' C' + (cx + R0) + ',' + (CY - 22) + ' ' + (cx + 24) + ',150 ' + cx + ',' + TP + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>'; } }); }
    else if (t === 'penguin') { var CY = 184, RX = 46, RY = 58; Object.assign(R, { rx: RX, ry: RY, cy: CY, headTop: CY - RY + 6, faceCy: CY - RY * 0.34, headW: 0.85, headHalf: 40, wingY: CY - 18, legY: CY + RY - 2, legXs: [138, 162], arms: false, armY: CY, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX, tailY: CY + RY, drawBody: function (bc, bd) { var lp = '<ellipse cx="' + (cx - RX + 2) + '" cy="' + (CY + 6) + '" rx="11" ry="30" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" transform="rotate(18 ' + (cx - RX + 2) + ' ' + (CY + 6) + ')"/>'; var rp = '<ellipse cx="' + (cx + RX - 2) + '" cy="' + (CY + 6) + '" rx="11" ry="30" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" transform="rotate(-18 ' + (cx + RX - 2) + ' ' + (CY + 6) + ')"/>'; return lp + rp + ell(cx, CY, RX, RY, bc, bd) + '<ellipse cx="' + cx + '" cy="' + (CY + 10) + '" rx="' + (RX * 0.6) + '" ry="' + (RY * 0.66) + '" fill="#ffffff" opacity="0.5"/>'; } }); }
    else if (t === 'doll') { var B = 222, BR = 40, M = 178, MR = 32, H = 140, HR2 = 26; Object.assign(R, { rx: BR, ry: BR, cy: B, headCx: cx, headTop: H - HR2 + 4, faceCy: H, headW: 0.7, headHalf: HR2, wingY: M, legY: B + BR - 4, legXs: [136, 164], armY: M, armLx: cx - MR, armRx: cx + MR, tailX: cx + BR, tailY: B + BR, drawBody: function (bc, bd) { return ell(cx, B, BR, BR, bc, bd) + ell(cx, M, MR, MR, bc, bd) + ell(cx, H, HR2, HR2, bc, bd); } }); }
    else if (t === 'dino') { var CY = 184, RX = 48, RY = 54; Object.assign(R, { rx: RX, ry: RY, cy: CY, headTop: CY - RY + 6, faceCy: CY - RY * 0.30, headW: 0.95, headHalf: 44, wingY: CY - 18, legY: CY + RY - 2, legXs: [132, 168], armY: CY - 2, armLx: cx - RX, armRx: cx + RX, tailX: cx + RX, tailY: CY + RY, drawBody: function (bc, bd) { var sp = ''; var c = darken(bc, 0.16);[[-16, -RY + 6], [2, -RY - 2], [20, -RY + 4], [36, -RY + 16]].forEach(function (p) { var dx = p[0], dy = p[1]; sp += '<path d="M' + (cx + dx - 7) + ',' + (CY + dy + 12) + ' L' + (cx + dx) + ',' + (CY + dy) + ' L' + (cx + dx + 7) + ',' + (CY + dy + 12) + ' Z" fill="' + c + '" stroke="' + bd + '" stroke-width="1.5" stroke-linejoin="round"/>'; }); return sp + ell(cx, CY, RX, RY, bc, bd); } }); }
    return R;
  }

  /* ---- beak / snout ---- */
  function snoutSVG(R, type, bc, bd) {
    if (!type || type === 'none') return '';
    var HC = R.headCx, fy = R.faceCy + 12;
    if (type === 'beak') return '<path d="M' + (HC - 8) + ',' + (fy - 1) + ' L' + (HC + 8) + ',' + (fy - 1) + ' L' + HC + ',' + (fy + 11) + ' Z" fill="#E8A33A" stroke="#b8801e" stroke-width="1.5" stroke-linejoin="round"/>';
    if (type === 'long_beak') return '<path d="M' + (HC - 7) + ',' + (fy - 1) + ' L' + (HC + 7) + ',' + (fy - 1) + ' L' + HC + ',' + (fy + 21) + ' Z" fill="#E8A33A" stroke="#b8801e" stroke-width="1.5" stroke-linejoin="round"/>';
    if (type === 'snout') return '<ellipse cx="' + HC + '" cy="' + (fy + 5) + '" rx="15" ry="11" fill="#ffffff" opacity="0.42"/><ellipse cx="' + HC + '" cy="' + (fy + 1) + '" rx="5" ry="3.5" fill="' + darken(bc, 0.34) + '"/><path d="M' + HC + ',' + (fy + 4) + ' v5 M' + (HC - 6) + ',' + (fy + 11) + ' q6,4 12,0" fill="none" stroke="' + darken(bc, 0.34) + '" stroke-width="1.6" stroke-linecap="round"/>';
    return '';
  }

  /* ---- procedural coat (seeded) ---- */
  function coatSVG(R, rnd, type, bc, bl) {
    if (!type || type === 'none') return '';
    var cx = R.cx, cy = R.cy, rx = R.rx, ry = R.ry, g = '';
    if (type === 'freckles') { var c = darken(bc, 0.16); for (var i = 0; i < 12; i++) { var x = R.headCx + (rnd() * 2 - 1) * R.headHalf * 0.8, y = R.faceCy + 9 + rnd() * 16; g += '<circle cx="' + x.toFixed(1) + '" cy="' + y.toFixed(1) + '" r="' + (1.3 + rnd() * 1.2).toFixed(1) + '" fill="' + c + '" opacity="0.55"/>'; } }
    else if (type === 'spots') { var c2 = darken(bc, 0.14); for (var j = 0; j < 14; j++) { var x2 = cx + (rnd() * 2 - 1) * rx, y2 = cy + (rnd() * 2 - 1) * ry, r2 = 2 + rnd() * 4; g += '<circle cx="' + x2.toFixed(1) + '" cy="' + y2.toFixed(1) + '" r="' + r2.toFixed(1) + '" fill="' + c2 + '" opacity="0.5"/>'; } }
    else if (type === 'patches') { for (var k = 0; k < 5; k++) { var x3 = cx + (rnd() * 2 - 1) * rx * 0.85, y3 = cy + (rnd() * 2 - 1) * ry * 0.85, r3 = 10 + rnd() * 16; g += '<ellipse cx="' + x3.toFixed(1) + '" cy="' + y3.toFixed(1) + '" rx="' + r3.toFixed(1) + '" ry="' + (r3 * (0.7 + rnd() * 0.5)).toFixed(1) + '" fill="' + bl + '" opacity="0.5"/>'; } }
    else if (type === 'stripes') { var c4 = darken(bc, 0.13); for (var m = 0; m < 5; m++) { var yy = cy - ry + (m + 1) * (2 * ry / 6); g += '<path d="M' + (cx - rx) + ',' + yy.toFixed(1) + ' q' + rx + ',' + (8 - rnd() * 16).toFixed(1) + ' ' + (2 * rx) + ',0" stroke="' + c4 + '" stroke-width="' + (4 + rnd() * 4).toFixed(1) + '" fill="none" opacity="0.5" stroke-linecap="round"/>'; } }
    return g;
  }

  /* ---- individual mark ---- */
  function markSVG(R, m, bc, acc) {
    var cx = R.cx, cy = R.cy, rx = R.rx, ry = R.ry, HC = R.headCx, FY = R.faceCy, HH = R.headHalf;
    if (m.type === 'scar') { var x = HC - HH * 0.5, y = FY - 2; return '<g stroke="' + darken(bc, 0.32) + '" stroke-width="2" stroke-linecap="round"><line x1="' + x + '" y1="' + (y - 12) + '" x2="' + (x + 5) + '" y2="' + (y + 12) + '"/>' + [-7, 1, 9].map(function (o) { return '<line x1="' + (x - 4) + '" y1="' + (y + o) + '" x2="' + (x + 8) + '" y2="' + (y + o - 2) + '"/>'; }).join('') + '</g>'; }
    if (m.type === 'crack') { var x2 = HC + HH * 0.42; return '<path d="M' + x2 + ',' + (FY - 14) + ' l5,8 l-5,3 l7,9" fill="none" stroke="' + (m.color ? EYE[m.color] || m.color : acc) + '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'; }
    if (m.type === 'patch') { var x3 = cx - rx * 0.42, y3 = cy + ry * 0.18; return '<g><rect x="' + (x3 - 9) + '" y="' + (y3 - 9) + '" width="18" height="18" rx="3" fill="' + darken(bc, 0.1) + '" stroke="' + darken(bc, 0.3) + '" stroke-width="1.5"/><line x1="' + (x3 - 9) + '" y1="' + y3 + '" x2="' + (x3 + 9) + '" y2="' + y3 + '" stroke="' + darken(bc, 0.3) + '" stroke-width="1" stroke-dasharray="2 2"/><line x1="' + x3 + '" y1="' + (y3 - 9) + '" x2="' + x3 + '" y2="' + (y3 + 9) + '" stroke="' + darken(bc, 0.3) + '" stroke-width="1" stroke-dasharray="2 2"/></g>'; }
    if (m.type === 'third_eye') return drawEye(HC, FY - 18, 8, EYE[m.color || 'fire'], 'round', EYE[m.color || 'fire']);
    if (m.type === 'heart_mark') { var x4 = HC + HH * 0.6, y4 = FY + 8; return '<path d="M' + x4 + ',' + (y4 + 2) + ' c-3,-4 -8,-1 -8,2 c0,3 5,6 8,9 c3,-3 8,-6 8,-9 c0,-3 -5,-6 -8,-2 z" fill="' + acc + '" opacity="0.85"/>'; }
    if (m.type === 'star_mark') { var x5 = HC - HH * 0.6, y5 = FY + 6; return star(x5, y5, 6, acc); }
    return '';
  }

  /* ---- emergent form (computed, used only for the eye-glow flag) ---- */
  function emergentForm(G) {
    var a = G.alignment;
    var horn = ['horns', 'horn'].includes(G.ears.type),
      darkW = ['demon', 'dragon', 'bat', 'phoenix'].includes(G.wings.type),
      darkA = ['fire', 'shadow', 'vortex', 'poison'].includes(G.aura.type);
    var holyW = ['angel', 'feathers', 'fairy'].includes(G.wings.type),
      holyA = ['holy', 'light', 'aurora'].includes(G.aura.type),
      uni = G.ears.type === 'unicorn';
    if (a <= -66 && (horn || darkW || darkA)) return { id: 'infernal', glow: '#FF5A2A' };
    if (a >= 66 && (holyW || holyA || uni)) return { id: 'serafico', glow: '#FFE6A0' };
    if (['ghost', 'star'].includes(G.body.type)) return { id: 'espectral', glow: '#9FE0FF' };
    return null;
  }

  /* ---- the renderer: genome -> layered SVG ---- */
  function buildPet(G) {
    var C = PAL[G.body.color] || PAL.cream, bc = C.m, bl = C.l, acc = ACC[G.accent.color] || ACC.blush, ey = EYE[G.eyes.color] || EYE.night;
    var R = rig(G), cx = R.cx, rx = R.rx, ry = R.ry, cy = R.cy;
    var hx = function (n) { return R.headCx + (n - 150) * R.headW; };
    var mood = band(G.alignment), form = emergentForm(G), rnd = rng(G.seed);
    var bd = C.d; if (mood === 'evil') bd = darken(bd, 0.18 * intensity(G));
    var eGlow = (G.eyes.color === 'fire' || G.eyes.color === 'gold' || !!form || Math.abs(G.alignment) >= 66);
    var wS = wingScale(G), hS = hornScale(G), tS = tailScale(G);
    var s = '<svg viewBox="0 0 300 340" style="width:100%;height:100%">';

    /* WINGS */
    var wy = R.wingY, wc = (G.wings.count == null ? 2 : G.wings.count);
    if (wc >= 1) s += wingG(cx + rx - 12, wy, true, G.wings.type, G.wings.color, wS);
    if (wc >= 2) s += wingG(cx - rx + 12, wy, false, G.wings.type, G.wings.color, wS);

    /* TAIL */
    var TBX = R.tailX, TBY = R.tailY, tpx = TBX - 8, tpy = TBY - 20, tail = '';
    if (G.tail.type === 'poof') tail = '<circle cx="' + (TBX - 6) + '" cy="' + (TBY - 18) + '" r="17" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>';
    else if (G.tail.type === 'curl') tail = '<path d="M' + tpx + ',' + tpy + ' q26,6 22,-16 q-3,-16 -16,-10" fill="none" stroke="' + bc + '" stroke-width="9" stroke-linecap="round"/>';
    else if (G.tail.type === 'tuft') tail = '<path d="M' + (TBX - 10) + ',' + (TBY - 22) + ' q22,-2 30,-20 q-2,16 -16,24 q-8,4 -14,-4 z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>';
    else if (G.tail.type === 'kitten') tail = '<path d="M' + (TBX - 8) + ',' + (TBY - 14) + ' q40,6 38,-32 q-2,-24 -20,-16" fill="none" stroke="' + bc + '" stroke-width="8" stroke-linecap="round"/>';
    else if (G.tail.type === 'flames') tail = '<path d="M' + (TBX - 6) + ',' + (TBY - 14) + ' q20,-4 20,-28 q10,12 5,24 q11,-6 11,-22 q9,16 -3,30 q-11,13 -33,4 z" fill="' + acc + '" stroke="' + darken(acc, 0.22) + '" stroke-width="1.5" stroke-linejoin="round"/>';
    else if (G.tail.type === 'spike') tail = '<path d="M' + (TBX - 8) + ',' + (TBY - 18) + ' l14,-4 l-6,-8 l14,-2 l-6,-9 l13,-3 l-8,-8" fill="none" stroke="' + bc + '" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>';
    else if (G.tail.type === 'plume') tail = '<path d="M' + (TBX - 8) + ',' + (TBY - 14) + ' q18,-12 40,-32 q-4,22 -34,30 z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/><path d="M' + (TBX - 4) + ',' + (TBY - 18) + ' q14,-10 30,-26" fill="none" stroke="' + bd + '" stroke-width="1.5" opacity="0.6"/>';
    else if (G.tail.type === 'mermaid') tail = '<path d="M' + (TBX - 10) + ',' + (TBY - 20) + ' q22,-4 34,-22 q-1,14 -10,18 q11,3 13,17 q-20,-7 -37,-5 z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>';
    else if (G.tail.type === 'bolt') tail = '<path d="M' + (TBX - 8) + ',' + (TBY - 18) + ' l16,-4 l-9,-7 l17,-6 l-9,-7 l15,-9" fill="none" stroke="' + acc + '" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>';
    else if (G.tail.type === 'imp') tail = '<path d="M' + (TBX - 8) + ',' + (TBY - 18) + ' q30,0 30,-26" fill="none" stroke="' + bc + '" stroke-width="6" stroke-linecap="round"/><path d="M' + (TBX + 22) + ',' + (TBY - 50) + ' l8,-2 l-4,10 l8,0 l-10,10 z" fill="' + bc + '" stroke="' + bd + '" stroke-width="1.5" stroke-linejoin="round"/>';
    else if (G.tail.type === 'star') tail = '<path d="M' + (TBX - 8) + ',' + (TBY - 18) + ' q26,4 24,-18" fill="none" stroke="' + bc + '" stroke-width="7" stroke-linecap="round"/>' + star(TBX + 16, TBY - 40, 8, acc);
    if (tail) s += scaleAbout(tail, tpx, tpy, tS);

    /* EARS / HORNS */
    var eTop = R.headTop;
    if (G.ears.type === 'round') s += '<circle cx="' + hx(118) + '" cy="' + (eTop - 2) + '" r="17" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/><circle cx="' + hx(182) + '" cy="' + (eTop - 2) + '" r="17" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/><circle cx="' + hx(118) + '" cy="' + (eTop - 1) + '" r="8" fill="' + acc + '" opacity="0.5"/><circle cx="' + hx(182) + '" cy="' + (eTop - 1) + '" r="8" fill="' + acc + '" opacity="0.5"/>';
    else if (G.ears.type === 'cat') s += '<path d="M' + hx(104) + ',' + (eTop + 8) + ' L' + hx(112) + ',' + (eTop - 26) + ' L' + hx(134) + ',' + (eTop + 2) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/><path d="M' + hx(196) + ',' + (eTop + 8) + ' L' + hx(188) + ',' + (eTop - 26) + ' L' + hx(166) + ',' + (eTop + 2) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>';
    else if (G.ears.type === 'bear') s += '<circle cx="' + hx(124) + '" cy="' + (eTop - 7) + '" r="12" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/><circle cx="' + hx(176) + '" cy="' + (eTop - 7) + '" r="12" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/><circle cx="' + hx(124) + '" cy="' + (eTop - 6) + '" r="5.5" fill="' + acc + '" opacity="0.5"/><circle cx="' + hx(176) + '" cy="' + (eTop - 6) + '" r="5.5" fill="' + acc + '" opacity="0.5"/>';
    else if (G.ears.type === 'fox') s += '<path d="M' + hx(112) + ',' + (eTop + 8) + ' L' + hx(98) + ',' + (eTop - 46) + ' L' + hx(138) + ',' + (eTop - 2) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/><path d="M' + hx(188) + ',' + (eTop + 8) + ' L' + hx(202) + ',' + (eTop - 46) + ' L' + hx(162) + ',' + (eTop - 2) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/><path d="M' + hx(108) + ',' + (eTop - 12) + ' L' + hx(101) + ',' + (eTop - 40) + ' L' + hx(120) + ',' + (eTop - 14) + ' Z" fill="' + darken(bc, 0.28) + '"/><path d="M' + hx(192) + ',' + (eTop - 12) + ' L' + hx(199) + ',' + (eTop - 40) + ' L' + hx(180) + ',' + (eTop - 14) + ' Z" fill="' + darken(bc, 0.28) + '"/>';
    else if (G.ears.type === 'floppy') s += '<ellipse cx="' + hx(106) + '" cy="' + (eTop + 22) + '" rx="13" ry="26" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/><ellipse cx="' + hx(194) + '" cy="' + (eTop + 22) + '" rx="13" ry="26" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>';
    else if (G.ears.type === 'pointed') s += '<path d="M' + hx(108) + ',' + (eTop + 6) + ' L' + hx(96) + ',' + (eTop - 34) + ' L' + hx(130) + ',' + (eTop - 2) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/><path d="M' + hx(192) + ',' + (eTop + 6) + ' L' + hx(204) + ',' + (eTop - 34) + ' L' + hx(170) + ',' + (eTop - 2) + ' Z" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" stroke-linejoin="round"/>';
    else if (G.ears.type === 'rabbit') { var lc = hx(130), rc = hx(170); s += '<ellipse cx="' + lc + '" cy="' + (eTop - 16) + '" rx="10" ry="30" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" transform="rotate(-9 ' + lc + ' ' + (eTop - 16) + ')"/><ellipse cx="' + rc + '" cy="' + (eTop - 16) + '" rx="10" ry="30" fill="' + bc + '" stroke="' + bd + '" stroke-width="2" transform="rotate(9 ' + rc + ' ' + (eTop - 16) + ')"/><ellipse cx="' + lc + '" cy="' + (eTop - 16) + '" rx="4.5" ry="21" fill="' + acc + '" opacity="0.5" transform="rotate(-9 ' + lc + ' ' + (eTop - 16) + ')"/><ellipse cx="' + rc + '" cy="' + (eTop - 16) + '" rx="4.5" ry="21" fill="' + acc + '" opacity="0.5" transform="rotate(9 ' + rc + ' ' + (eTop - 16) + ')"/>'; }
    else if (G.ears.type === 'unicorn') s += scaleAbout('<path d="M' + hx(150) + ',' + (eTop + 2) + ' L' + hx(143) + ',' + (eTop - 36) + ' L' + hx(157) + ',' + (eTop - 36) + ' Z" fill="' + acc + '" stroke="' + darken(acc, 0.22) + '" stroke-width="1.5" stroke-linejoin="round"/><path d="M' + hx(146) + ',' + (eTop - 10) + ' l8,-1 M' + hx(147) + ',' + (eTop - 19) + ' l6,-1 M' + hx(148) + ',' + (eTop - 28) + ' l4,-1" stroke="' + darken(acc, 0.28) + '" stroke-width="1.6" stroke-linecap="round"/>', R.headCx, eTop, hS);
    else if (G.ears.type === 'horn') s += scaleAbout('<path d="M' + hx(124) + ',' + (eTop + 2) + ' q-4,-20 6,-26" fill="none" stroke="' + bd + '" stroke-width="6" stroke-linecap="round"/><path d="M' + hx(176) + ',' + (eTop + 2) + ' q4,-20 -6,-26" fill="none" stroke="' + bd + '" stroke-width="6" stroke-linecap="round"/>', R.headCx, eTop, hS);
    else if (G.ears.type === 'horns') s += scaleAbout('<path d="M' + hx(122) + ',' + (eTop + 4) + ' q-16,-22 -2,-40 q6,16 16,22" fill="' + bd + '" stroke="' + darken(bd, 0.2) + '" stroke-width="1.5" stroke-linejoin="round"/><path d="M' + hx(178) + ',' + (eTop + 4) + ' q16,-22 2,-40 q-6,16 -16,22" fill="' + bd + '" stroke="' + darken(bd, 0.2) + '" stroke-width="1.5" stroke-linejoin="round"/>', R.headCx, eTop, hS);

    /* LEGS */
    var legY = R.legY, legStyle = (G.legs && G.legs.present) ? (G.legs.style || 'oval') : 'none';
    var footAt = function (fx) {
      if (legStyle === 'oval') return '<ellipse cx="' + fx + '" cy="' + legY + '" rx="13" ry="9" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>';
      if (legStyle === 'paw') return '<ellipse cx="' + fx + '" cy="' + legY + '" rx="13" ry="9" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/><ellipse cx="' + fx + '" cy="' + (legY + 1) + '" rx="6" ry="4" fill="' + acc + '" opacity="0.5"/>';
      if (legStyle === 'claw') return '<ellipse cx="' + fx + '" cy="' + (legY - 1) + '" rx="12" ry="8" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>' + [-6, 0, 6].map(function (o) { return '<path d="M' + (fx + o) + ',' + (legY + 5) + ' l-1.5,5 l3,0 z" fill="' + bd + '"/>'; }).join('');
      if (legStyle === 'boot') return '<rect x="' + (fx - 6) + '" y="' + (legY - 13) + '" width="12" height="11" rx="2.5" fill="' + darken(bc, 0.22) + '" stroke="' + bd + '" stroke-width="1.5"/><rect x="' + (fx - 12) + '" y="' + (legY - 3) + '" width="24" height="9" rx="4.5" fill="' + darken(bc, 0.22) + '" stroke="' + bd + '" stroke-width="1.5"/>';
      return '';
    };
    if (legStyle !== 'none') R.legXs.forEach(function (fx) { s += footAt(fx); });

    /* ARMS */
    if (R.arms && G.arms.present) {
      var ay = R.armY, lx = R.armLx, rxp = R.armRx;
      if (G.arms.style === 'tentacle') {
        s += '<path d="M' + (lx + 8) + ',' + ay + ' q-20,8 -12,26 q5,10 -4,15" fill="none" stroke="' + bc + '" stroke-width="9" stroke-linecap="round"/><path d="M' + (rxp - 8) + ',' + ay + ' q20,8 12,26 q-5,10 4,15" fill="none" stroke="' + bc + '" stroke-width="9" stroke-linecap="round"/>';
      } else {
        s += '<line x1="' + (lx + 8) + '" y1="' + ay + '" x2="' + (lx - 6) + '" y2="' + (ay + 20) + '" stroke="' + bc + '" stroke-width="11" stroke-linecap="round"/><line x1="' + (rxp - 8) + '" y1="' + ay + '" x2="' + (rxp + 6) + '" y2="' + (ay + 20) + '" stroke="' + bc + '" stroke-width="11" stroke-linecap="round"/>';
        if (G.arms.style === 'paw') s += '<circle cx="' + (lx - 6) + '" cy="' + (ay + 20) + '" r="7" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/><circle cx="' + (rxp + 6) + '" cy="' + (ay + 20) + '" r="7" fill="' + bc + '" stroke="' + bd + '" stroke-width="2"/>';
        else { var claw = function (hxv, dir) { var c = ''; for (var k = -1; k < 2; k++) { var px = hxv + dir * 3 + k * 4; c += '<path d="M' + px + ',' + (ay + 20) + ' l' + (dir * 1.5) + ',9 l-3,0 z" fill="' + bd + '"/>'; } return c; }; s += claw(lx - 6, -1) + claw(rxp + 6, 1); }
      }
    }

    /* BODY */
    s += R.drawBody(bc, bd);

    /* belly accent */
    if (G.accent.mark === 'barriga') s += '<ellipse cx="' + cx + '" cy="' + (cy + 14) + '" rx="' + (rx * 0.52) + '" ry="' + (ry * 0.5) + '" fill="' + bl + '"/>';
    else if (G.accent.mark === 'pintas') s += '<circle cx="' + (cx - 22) + '" cy="' + (cy + 18) + '" r="6" fill="' + bl + '"/><circle cx="' + (cx + 18) + '" cy="' + (cy + 26) + '" r="5" fill="' + bl + '"/><circle cx="' + cx + '" cy="' + (cy + 6) + '" r="4" fill="' + bl + '"/>';

    /* coat */
    if (G.pattern && G.pattern.type && G.pattern.type !== 'none') {
      var clip = 'bclip' + (G.seed >>> 0);
      s += '<clipPath id="' + clip + '">' + R.drawBody('#000', '#000') + '</clipPath>';
      s += '<g clip-path="url(#' + clip + ')">' + coatSVG(R, rnd, G.pattern.type, bc, bl) + '</g>';
    }

    /* FACE */
    var es = 12, eY = R.faceCy, HC = R.headCx, HW = R.headW;
    var xs = G.eyes.count === 1 ? [HC] : G.eyes.count === 2 ? [HC - (es + 7) * HW, HC + (es + 7) * HW] : [HC - (es * 2 + 6) * HW, HC, HC + (es * 2 + 6) * HW];
    if (G.accent.cheeks) s += '<ellipse cx="' + (HC - R.headHalf * 0.62) + '" cy="' + (eY + es + 4) + '" rx="9" ry="6" fill="' + acc + '" opacity="0.55"/><ellipse cx="' + (HC + R.headHalf * 0.62) + '" cy="' + (eY + es + 4) + '" rx="9" ry="6" fill="' + acc + '" opacity="0.55"/>';
    var ey2 = EYE[G.eyes.color2] || ey, estyle = G.eyes.style || 'round';
    xs.forEach(function (x, i) { var ec = (G.eyes.hetero && i % 2 === 1) ? ey2 : ey; s += drawEye(x, eY, es, ec, estyle, eGlow ? ec : null); });
    if (mood === 'evil') xs.forEach(function (x) { s += '<line x1="' + (x - es * 0.7) + '" y1="' + (eY - es - 3) + '" x2="' + (x + es * 0.4) + '" y2="' + (eY - es + 1) + '" stroke="' + bd + '" stroke-width="2.5" stroke-linecap="round"/>'; });

    /* SNOUT / MOUTH */
    var snout = G.snout || 'none';
    if (snout === 'none' && R.type === 'bird') snout = 'beak';
    if (snout === 'none' && R.type === 'dino') snout = 'snout';
    var my = eY + es + 7;
    if (snout === 'none') {
      if (mood === 'good') s += '<path d="M' + (HC - 8) + ',' + my + ' Q' + HC + ',' + (my + 8) + ' ' + (HC + 8) + ',' + my + '" fill="none" stroke="' + bd + '" stroke-width="2.5" stroke-linecap="round"/>';
      else if (mood === 'evil') s += '<path d="M' + (HC - 8) + ',' + (my + 3) + ' Q' + HC + ',' + (my - 3) + ' ' + (HC + 8) + ',' + (my + 3) + '" fill="none" stroke="' + bd + '" stroke-width="2.5" stroke-linecap="round"/><path d="M' + (HC + 4) + ',' + (my + 2) + ' l3,5 l-5,0 z" fill="#fff"/>';
      else s += '<line x1="' + (HC - 6) + '" y1="' + my + '" x2="' + (HC + 6) + '" y2="' + my + '" stroke="' + bd + '" stroke-width="2.5" stroke-linecap="round"/>';
    } else s += snoutSVG(R, snout, bc, bd);

    if (G.extra && G.extra.antena) s += '<line x1="' + HC + '" y1="' + (R.headTop - 6) + '" x2="' + HC + '" y2="' + (R.headTop - 26) + '" stroke="' + bc + '" stroke-width="3.5" stroke-linecap="round"/><circle cx="' + HC + '" cy="' + (R.headTop - 28) + '" r="6" fill="' + acc + '"/>';

    /* MARKS */
    if (G.marks && G.marks.length) G.marks.forEach(function (m) { s += markSVG(R, m, bc, acc); });

    s += '</svg>';
    return s;
  }

  /* ---- aura (tsParticles) ---- */
  function intenMult(G) { return ({ low: 0.6, medium: 1, high: 1.5, max: 2.2 })[G.aura.inten] || 1; }
  function reducedMotion() { try { return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches; } catch (e) { return false; } }
  var SPEED_CAP = 1.1;

  function registerShapes() {
    if (typeof tsParticles === 'undefined' || tsParticles.__petShapes) return;
    tsParticles.addShape('heart', function (c, p, r) { var z = r / 14; c.moveTo(0, 11 * z); c.bezierCurveTo(-2 * z, 8 * z, -14 * z, 0, -14 * z, -6 * z); c.bezierCurveTo(-14 * z, -13 * z, -6 * z, -14 * z, 0, -7 * z); c.bezierCurveTo(6 * z, -14 * z, 14 * z, -13 * z, 14 * z, -6 * z); c.bezierCurveTo(14 * z, 0, 2 * z, 8 * z, 0, 11 * z); });
    tsParticles.addShape('petal', function (c, p, r) { c.moveTo(0, -r); c.quadraticCurveTo(r * 0.95, 0, 0, r); c.quadraticCurveTo(-r * 0.95, 0, 0, -r); });
    tsParticles.__petShapes = true;
  }

  function auraOptions(G) {
    var a = G.aura, RM = reducedMotion();
    var dirMap = { up: 'top', down: 'bottom', drift: 'none', swirl: 'none' };
    var count = clamp(Math.round((a.density || 0) * intenMult(G)), 0, 300);
    var op = clamp(a.opacity || 0.6, 0.05, 1), sz = clamp(a.size || 4, 1, 30);
    var spd = clamp((a.speed || 0.4), 0, SPEED_CAP) * (RM ? 0.25 : 1);
    var twinkle = !!a.twinkle && !RM, spin = !!a.spin && !RM, hue = !!a.hueShift && !RM;
    var move = { enable: true, direction: dirMap[a.direction] || 'none', speed: Math.max(0.05, spd), random: a.direction !== 'up' && a.direction !== 'down', straight: false, outModes: { default: 'out' } };
    if (a.direction === 'down') move.gravity = { enable: true, acceleration: Math.max(0.5, spd * 2.2) };
    if (a.direction === 'swirl') move.spin = { enable: true, acceleration: Math.max(0.6, spd * 2.4) };
    return {
      fullScreen: { enable: false }, detectRetina: true, fpsLimit: 60, background: { color: 'transparent' },
      particles: {
        number: { value: count, density: { enable: false } },
        color: hue ? { value: '#ff5e7e', animation: { h: { enable: true, speed: 5, sync: false } } } : { value: a.color },
        shape: { type: a.shape || 'circle', options: { polygon: { sides: 6 }, star: { sides: 5, inset: 2 } } },
        opacity: { value: { min: Math.max(0.06, op * 0.55), max: op }, animation: twinkle ? { enable: true, speed: 0.5, sync: false, minimumValue: Math.max(0.06, op * 0.55) } : { enable: false } },
        size: { value: { min: Math.max(1, sz * 0.45), max: sz } },
        move: move,
        shadow: { enable: (a.glow || 0) > 0.02, color: a.color, blur: (a.glow || 0) * 22 },
        rotate: { value: { min: 0, max: 360 }, direction: 'random', animation: { enable: spin, speed: 3, sync: false } },
      },
    };
  }

  var auraContainer = null;
  async function paintAura(G, auraEl, haloEl) {
    if (haloEl) {
      if (G.aura.halo) { haloEl.style.display = 'block'; haloEl.style.background = 'radial-gradient(circle at 50% 56%, ' + hexA(G.aura.color, 0.85) + ', transparent 60%)'; }
      else haloEl.style.display = 'none';
    }
    if (!auraEl) return;
    auraEl.style.mixBlendMode = (!G.aura.blend || G.aura.blend === 'normal') ? 'normal' : G.aura.blend;
    if (auraContainer) { try { auraContainer.destroy(); } catch (e) {} auraContainer = null; }
    if (G.aura.type === 'none' || typeof tsParticles === 'undefined') return;
    registerShapes();
    try { auraContainer = await tsParticles.load({ element: auraEl, options: auraOptions(G) }); }
    catch (e) { console.warn('petabit aura:', e); }
  }

  /* ---- public entry ---- */
  function renderPet(genome, holderEl, auraEl, haloEl) {
    if (!genome || !holderEl) return;
    try { holderEl.innerHTML = buildPet(genome); }
    catch (e) { console.warn('petabit render:', e); }
    paintAura(genome, auraEl, haloEl);
  }

  /**
   * Render every [data-petabit-pet] container whose genome hasn't been drawn
   * yet (or changed). Safe to call repeatedly — after page load and after every
   * Livewire morph. Each container holds the genome JSON in data-genome and
   * .pet-holder / .pet-aura / .pet-halo children.
   */
  function mountAll(root) {
    var scope = root || document;
    var nodes = scope.querySelectorAll('[data-petabit-pet]');
    nodes.forEach(function (el) {
      var raw = el.getAttribute('data-genome');
      if (!raw || el.__pbGenome === raw) return; // unchanged → skip
      var genome;
      try { genome = JSON.parse(raw); } catch (e) { return; }
      el.__pbGenome = raw;
      renderPet(
        genome,
        el.querySelector('.pet-holder'),
        el.querySelector('.pet-aura'),
        el.querySelector('.pet-halo')
      );
    });
  }

  window.PetabitRenderer = { renderPet: renderPet, buildPet: buildPet, mountAll: mountAll };
})();
